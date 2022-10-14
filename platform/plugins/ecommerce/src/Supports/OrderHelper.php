<?php

namespace Botble\Ecommerce\Supports;

use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\PDF as PDFHelper;
use BaseHelper;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\EmailHandler as EmailHandlerSupport;
use Botble\Ecommerce\Cart\CartItem;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Cart;
use EcommerceHelper as EcommerceHelperFacade;
use EmailHandler;
use Exception;
use File;
use Html;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Log;
use PDF;
use RvMedia;
use Throwable;
use Validator;

class OrderHelper
{
    /**
     * @param string|array $orderIds
     * @param string|null $chargeId
     *
     * @return BaseModel|bool
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function processOrder($orderIds, ?string $chargeId = null)
    {
        $orderIds = (array)$orderIds;

        $orders = app(OrderInterface::class)->allBy([['id', 'IN', $orderIds]]);

        if (!$orders->count()) {
            return false;
        }

        foreach ($orders as $order) {
            if ($order->histories()->where('action', 'create_order')->count()) {
                return false;
            }
        }

        if ($chargeId) {
            $payments = app(PaymentInterface::class)->allBy([
                ['charge_id', '=', $chargeId],
                ['order_id', 'IN', $orderIds],
            ]);

            if ($payments) {
                foreach ($orders as $order) {
                    $payment = $payments->firstWhere('order_id', $order->id);
                    if ($payment) {
                        $order->payment_id = $payment->id;
                        $order->save();
                    }
                }
            }
        }

        Cart::instance('cart')->destroy();
        session()->forget('applied_coupon_code');

        session(['order_id' => Arr::first($orderIds)]);

        if (is_plugin_active('marketplace')) {
            apply_filters(SEND_MAIL_AFTER_PROCESS_ORDER_MULTI_DATA, $orders);
        } else {
            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('admin_new_order')) {
                $this->setEmailVariables($orders->first());
                $mailer->sendUsingTemplate('admin_new_order', get_admin_email()->toArray());
            }

            // Temporarily only send emails with the first order
            $this->sendOrderConfirmationEmail($orders->first(), true);
        }

        session(['order_id' => $orders->first()->id]);

        foreach ($orders as $order) {
            app(OrderHistoryInterface::class)->createOrUpdate([
                'action'      => 'create_order',
                'description' => trans('plugins/ecommerce::order.new_order_from', [
                    'order_id' => get_order_code($order->id),
                    'customer' => BaseHelper::clean($order->user->name ?: $order->address->name),
                ]),
                'order_id'    => $order->id,
            ]);
        }

        foreach ($orders as $order) {
            foreach ($order->products as $orderProduct) {
                $product = $orderProduct->product->original_product;

                $flashSale = $product->latestFlashSales()->first();
                if (!$flashSale) {
                    continue;
                }

                $flashSale->products()->detach([$product->id]);
                $flashSale->products()->attach([
                    $product->id => [
                        'price'    => $flashSale->pivot->price,
                        'quantity' => (int)$flashSale->pivot->quantity,
                        'sold'     => (int)$flashSale->pivot->sold + $orderProduct->qty,
                    ],
                ]);
            }
        }

        return $orders;
    }

    /**
     * @param Order $order
     *
     * @return EmailHandlerSupport
     * @throws Throwable
     */
    public function setEmailVariables(Order $order): EmailHandlerSupport
    {
        return EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'store_address'        => get_ecommerce_setting('store_address'),
                'store_phone'          => get_ecommerce_setting('store_phone'),
                'order_id'             => str_replace('#', '', get_order_code($order->id)),
                'order_token'          => $order->token,
                'customer_name'        => BaseHelper::clean($order->user->name ?: $order->address->name),
                'customer_email'       => $order->user->email ?: $order->address->email,
                'customer_phone'       => $order->user->phone ?: $order->address->phone,
                'customer_address'     => $order->full_address,
                'product_list'         => view('plugins/ecommerce::emails.partials.order-detail', compact('order'))
                    ->render(),
                'shipping_method'      => $order->shipping_method_name,
                'payment_method'       => $order->payment->payment_channel->label(),
                'order_delivery_notes' => view(
                    'plugins/ecommerce::emails.partials.order-delivery-notes',
                    compact('order')
                )
                    ->render(),
            ]);
    }

    /**
     * @param Order $order
     * @param bool $saveHistory
     *
     * @return boolean
     * @throws Throwable
     */
    public function sendOrderConfirmationEmail(Order $order, bool $saveHistory = false): bool
    {
        try {
            $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
            if ($mailer->templateEnabled('customer_new_order')) {
                $this->setEmailVariables($order);

                EmailHandler::send(
                    $mailer->getTemplateContent('customer_new_order'),
                    $mailer->getTemplateSubject('customer_new_order'),
                    $order->user->email ?: $order->address->email
                );

                if ($saveHistory) {
                    app(OrderHistoryInterface::class)->createOrUpdate([
                        'action'      => 'send_order_confirmation_email',
                        'description' => trans('plugins/ecommerce::order.confirmation_email_was_sent_to_customer'),
                        'order_id'    => $order->id,
                    ]);
                }
            }

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return false;
    }

    /**
     * @param Order $order
     *
     * @return PDFHelper
     */
    public function makeInvoicePDF(Order $order): PDFHelper
    {
        $fontsPath = storage_path('fonts');
        if (!File::isDirectory($fontsPath)) {
            File::makeDirectory($fontsPath);
        }

        $template = 'plugins/ecommerce::invoices.template';
        if (view()->exists('plugins/ecommerce/invoice::template')) {
            $template = 'plugins/ecommerce/invoice::template';
        }

        if (get_ecommerce_setting('invoice_support_arabic_language', 0) == 1) {
            $templateHtml = view($template, compact('order'))->render();

            $arabic = new Arabic();
            $p = $arabic->arIdentify($templateHtml);

            for ($i = count($p) - 1; $i >= 0; $i -= 2) {
                $utf8ar = $arabic->utf8Glyphs(substr($templateHtml, $p[$i - 1], $p[$i] - $p[$i - 1]));
                $templateHtml = substr_replace($templateHtml, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
            }

            $pdf = PDF::loadHTML($templateHtml, 'UTF-8');
        } else {
            $pdf = PDF::loadView($template, compact('order'), [], 'UTF-8');
        }

        return $pdf
            ->setPaper('a4')
            ->setWarnings(false)
            ->setOption('tempDir', storage_path('app'))
            ->setOption('logOutputFile', storage_path('logs/pdf.log'))
            ->setOption('isRemoteEnabled', true);
    }

    /**
     * @param Order $order
     *
     * @return string
     */
    public function generateInvoice(Order $order): string
    {
        $folderPath = storage_path('app/public');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath);
        }

        $invoice = $folderPath . '/invoice-order-' . get_order_code($order->id) . '.pdf';

        if (File::exists($invoice)) {
            return $invoice;
        }

        $this->makeInvoicePDF($order)->save($invoice);

        return $invoice;
    }

    /**
     * @param Order $order
     *
     * @return Response
     */
    public function downloadInvoice(Order $order): Response
    {
        return $this->makeInvoicePDF($order)->download('invoice-order-' . get_order_code($order->id) . '.pdf');
    }

    /**
     * @param Order $order
     *
     * @return Response
     */
    public function streamInvoice(Order $order): Response
    {
        return $this->makeInvoicePDF($order)->stream();
    }

    /**
     * @param string $method
     * @param null $option
     *
     * @return array|null|string
     */
    public function getShippingMethod(string $method, $option = null)
    {
        $name = null;

        switch ($method) {
            default:
                if ($option) {
                    $rule = app(ShippingRuleInterface::class)->findById($option);
                    if ($rule) {
                        $name = $rule->name;
                    }
                }

                if (empty($name)) {
                    $name = trans('plugins/ecommerce::order.default');
                }

                break;
        }

        return $name;
    }

    /**
     * @param OrderHistory|ShipmentHistory $history
     *
     * @return mixed
     */
    public function processHistoryVariables($history)
    {
        if (empty($history)) {
            return null;
        }

        $variables = [
            'order_id'  => Html::link(
                route('orders.edit', $history->order->id),
                get_order_code($history->order->id) . ' <i class="fa fa-external-link-alt"></i>',
                ['target' => '_blank'],
                null,
                false
            )
                ->toHtml(),
            'user_name' => $history->user_id === 0 ? trans('plugins/ecommerce::order.system') :
                BaseHelper::clean($history->user ? $history->user->name : (
                    $history->order->user->name ?:
                        $history->order->address->name
                )),
        ];

        $content = $history->description;

        foreach ($variables as $key => $value) {
            $content = str_replace('% ' . $key . ' %', $value, $content);
            $content = str_replace('%' . $key . '%', $value, $content);
            $content = str_replace('% ' . $key . '%', $value, $content);
            $content = str_replace('%' . $key . ' %', $value, $content);
        }

        return $content;
    }

    /**
     * @param string|null $token
     * @param string|array $data
     *
     * @return array
     */
    public function setOrderSessionData(?string $token, $data): array
    {
        if (!$token) {
            $token = $this->getOrderSessionToken();
        }

        $data = array_replace_recursive($this->getOrderSessionData($token), $data);

        $data = $this->cleanData($data);

        session([md5('checkout_address_information_' . $token) => $data]);

        return $data;
    }

    /**
     * @return string
     */
    public function getOrderSessionToken(): string
    {
        if (session()->has('tracked_start_checkout')) {
            $token = session()->get('tracked_start_checkout');
        } else {
            $token = md5(Str::random(40));
            session(['tracked_start_checkout' => $token]);
        }

        return $token;
    }

    /**
     * @param string|null $token
     *
     * @return array
     */
    public function getOrderSessionData(?string $token = null): array
    {
        if (!$token) {
            $token = $this->getOrderSessionToken();
        }

        $data = [];
        $sessionKey = md5('checkout_address_information_' . $token);
        if (session()->has($sessionKey)) {
            $data = session($sessionKey);
        }

        return $this->cleanData($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function cleanData(array $data): array
    {
        foreach ($data as $key => $item) {
            if (!is_string($item)) {
                continue;
            }

            $data[$key] = BaseHelper::clean($item);
        }

        return $data;
    }

    /**
     * @param string|null $token
     * @param string|array $data
     *
     * @return array
     */
    public function mergeOrderSessionData(?string $token, $data): array
    {
        if (!$token) {
            $token = $this->getOrderSessionToken();
        }

        $data = array_merge($this->getOrderSessionData($token), $data);

        session([md5('checkout_address_information_' . $token) => $data]);

        return $this->cleanData($data);
    }

    /**
     * @param string|null $token
     */
    public function clearSessions(?string $token)
    {
        Cart::instance('cart')->destroy();
        session()->forget('applied_coupon_code');
        session()->forget('order_id');
        session()->forget(md5('checkout_address_information_' . $token));
        session()->forget('tracked_start_checkout');
    }

    /**
     * @param Product $product
     * @param Request $request
     *
     * @return array
     */
    public function handleAddCart(Product $product, Request $request): array
    {
        $parentProduct = $product->original_product;

        $image = $product->image ?: $parentProduct->image;

        /**
         * Add cart to session
         */
        Cart::instance('cart')->add(
            $product->id,
            BaseHelper::clean($parentProduct->name),
            $request->input('qty', 1),
            $product->original_price,
            [
                'image'      => RvMedia::getImageUrl($image, 'thumb', false, RvMedia::getDefaultImage()),
                'attributes' => $product->is_variation ? $product->variation_attributes : '',
                'taxRate'    => $parentProduct->tax->percentage,
                'extras'     => $request->input('extras', []),
            ]
        );

        /**
         * prepare data for response
         */
        $cartItems = [];

        foreach (Cart::instance('cart')->content() as $item) {
            $cartItems[] = $item;
        }

        return $cartItems;
    }

    /**
     * @param int $currentUserId
     * @param array $sessionData
     * @param Request $request
     *
     * @return array
     */
    public function processAddressOrder(int $currentUserId, array $sessionData, Request $request): array
    {
        $address = null;

        if ($currentUserId && !Arr::get($sessionData, 'address_id')) {
            $address = app(AddressInterface::class)->getFirstBy([
                'customer_id' => auth('customer')->id(),
                'is_default'  => true,
            ]);

            if ($address) {
                $sessionData['address_id'] = $address->id;
            }
        } elseif ($request->input('address.address_id') && $request->input('address.address_id') !== 'new') {
            $address = app(AddressInterface::class)->findById($request->input('address.address_id'));
            if (!empty($address)) {
                $sessionData['address_id'] = $address->id;
            }
        }

        if (Arr::get($sessionData, 'address_id') && Arr::get($sessionData, 'address_id') !== 'new') {
            $address = app(AddressInterface::class)->findById(Arr::get($sessionData, 'address_id'));
        }

        if (!empty($address)) {
            $addressData = [
                'name'     => $address->name,
                'phone'    => $address->phone,
                'email'    => $address->email,
                'country'  => $address->country,
                'state'    => $address->state,
                'city'     => $address->city,
                'address'  => $address->address,
                'zip_code' => $address->zip_code,
                'order_id' => Arr::get($sessionData, 'created_order_id', 0),
            ];
        } elseif ((array)$request->input('address', [])) {
            $addressData = array_merge(
                ['order_id' => Arr::get($sessionData, 'created_order_id', 0)],
                (array)$request->input('address', [])
            );
        } else {
            $addressData = [
                'name'     => Arr::get($sessionData, 'name'),
                'phone'    => Arr::get($sessionData, 'phone'),
                'email'    => Arr::get($sessionData, 'email'),
                'country'  => Arr::get($sessionData, 'country'),
                'state'    => Arr::get($sessionData, 'state'),
                'city'     => Arr::get($sessionData, 'city'),
                'address'  => Arr::get($sessionData, 'address'),
                'zip_code' => Arr::get($sessionData, 'zip_code'),
                'order_id' => Arr::get($sessionData, 'created_order_id', 0),
            ];
        }

        $addressData = $this->cleanData($addressData);

        if ($addressData && !empty($addressData['name']) && !empty($addressData['phone']) && !empty($addressData['address'])) {
            if (!isset($sessionData['created_order_address'])) {
                $createdOrderAddress = $this->createOrderAddress($addressData);
                if ($createdOrderAddress) {
                    $sessionData['created_order_address'] = true;
                    $sessionData['created_order_address_id'] = $createdOrderAddress->id;
                }
            } elseif (Arr::get($sessionData, 'created_order_address_id')) {
                $createdOrderAddress = $this->createOrderAddress(
                    $addressData,
                    $sessionData['created_order_address_id']
                );
                $sessionData['created_order_address'] = true;
                $sessionData['created_order_address_id'] = $createdOrderAddress->id;
            }
        }

        return $sessionData;
    }

    /**
     * @param array $data
     * @param int|null $orderAddressId
     *
     * @return false|mixed
     */
    protected function createOrderAddress(array $data, ?int $orderAddressId = null)
    {
        if ($orderAddressId) {
            return app(OrderAddressInterface::class)->createOrUpdate($data, ['id' => $orderAddressId]);
        }

        $rules = [
            'name'    => 'required|max:255',
            'email'   => 'email|nullable|max:60',
            'phone'   => EcommerceHelperFacade::getPhoneValidationRule(),
            'state'   => 'required|max:120',
            'city'    => 'required|max:120',
            'address' => 'required|max:120',
        ];

        if (EcommerceHelperFacade::isZipCodeEnabled()) {
            $rules['zip_code'] = 'required|max:20';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return false;
        }

        return app(OrderAddressInterface::class)->create($data);
    }

    /**
     * @param array $products
     * @param array $sessionData
     *
     * @return array
     */
    public function processOrderProductData($products, array $sessionData): array
    {
        $createdOrderProduct = Arr::get($sessionData, 'created_order_product');

        $cartItems = $products['products']->pluck('cartItem');

        $lastUpdatedAt = Cart::instance('cart')->getLastUpdatedAt();

        // Check latest updated at of cart
        if (!$createdOrderProduct || !$createdOrderProduct->eq($lastUpdatedAt)) {
            $orderProducts = app(OrderProductInterface::class)->allBy(['order_id' => $sessionData['created_order_id']]);
            $productIds = [];
            foreach ($cartItems as $cartItem) {
                $productByCartItem = $products['products']->firstWhere('id', $cartItem->id);
                $data = [
                    'order_id'     => $sessionData['created_order_id'],
                    'product_id'   => $cartItem->id,
                    'product_name' => $cartItem->name,
                    'qty'          => $cartItem->qty,
                    'weight'       => $productByCartItem->weight * $cartItem->qty,
                    'price'        => $cartItem->price,
                    'tax_amount'   => $cartItem->tax,
                    'options'      => [],
                    'product_type' => $productByCartItem->product_type,
                ];

                if ($cartItem->options->extras) {
                    $data['options'] = $cartItem->options->extras;
                }

                $orderProduct = $orderProducts->firstWhere('product_id', $cartItem->id);

                if ($orderProduct) {
                    $orderProduct->fill($data);
                    $orderProduct->save();
                } else {
                    app(OrderProductInterface::class)->create($data);
                }

                $productIds[] = $cartItem->id;
            }

            // Delete orderProducts not exists;
            foreach ($orderProducts as $orderProduct) {
                if (!in_array($orderProduct->product_id, $productIds)) {
                    $orderProduct->delete();
                }
            }

            $sessionData['created_order_product'] = $lastUpdatedAt;
        }

        return $sessionData;
    }

    /**
     * @param       $sessionData
     * @param       $request
     * @param       $cartItems
     * @param       $order
     * @param array $generalData
     *
     * @return array
     */
    public function processOrderInCheckout(
        $sessionData,
        $request,
        $cartItems,
        $order,
        array $generalData
    ): array {
        $createdOrder = Arr::get($sessionData, 'created_order');
        $createdOrderId = Arr::get($sessionData, 'created_order_id');

        $lastUpdatedAt = Cart::instance('cart')->getLastUpdatedAt();

        $data = array_merge([
            'amount'          => Cart::instance('cart')->rawTotalByItems($cartItems),
            'shipping_method' => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
            'shipping_option' => $request->input('shipping_option'),
            'tax_amount'      => Cart::instance('cart')->rawTaxByItems($cartItems),
            'sub_total'       => Cart::instance('cart')->rawSubTotalByItems($cartItems),
            'coupon_code'     => session()->get('applied_coupon_code'),
        ], $generalData);

        if ($createdOrder && $createdOrderId) {
            if ($order && (is_string($createdOrder) || !$createdOrder->eq($lastUpdatedAt))) {
                $order->fill($data);
            }
        }

        if (!$order) {
            $data = array_merge($data, [
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'status'          => OrderStatusEnum::PENDING,
                'is_finished'     => false,
            ]);
            $order = app(OrderInterface::class)->createOrUpdate($data);
        }

        $sessionData['created_order'] = $lastUpdatedAt; // insert last updated at in here
        $sessionData['created_order_id'] = $order->id;

        return [$sessionData, $order];
    }

    /**
     * @param Request $request
     * @param int $currentUserId
     * @param string $token
     * @param CartItem[] $cartItems
     *
     * @return mixed
     */
    public function createOrder(Request $request, int $currentUserId, string $token, array $cartItems)
    {
        $request->merge([
            'amount'          => Cart::instance('cart')->rawTotalByItems($cartItems),
            'user_id'         => $currentUserId,
            'shipping_method' => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
            'shipping_option' => $request->input('shipping_option'),
            'shipping_amount' => 0,
            'tax_amount'      => Cart::instance('cart')->rawTaxByItems($cartItems),
            'sub_total'       => Cart::instance('cart')->rawSubTotalByItems($cartItems),
            'coupon_code'     => session()->get('applied_coupon_code'),
            'discount_amount' => 0,
            'status'          => OrderStatusEnum::PENDING,
            'is_finished'     => false,
            'token'           => $token,
        ]);

        return app(OrderInterface::class)->createOrUpdate($request->input());
    }

    /**
     * @param Order $order
     *
     * @return bool
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function confirmPayment(Order $order): bool
    {
        $payment = $order->payment;

        if (!$payment) {
            return false;
        }

        $payment->status = PaymentStatusEnum::COMPLETED;
        $payment->amount = $payment->amount ?: 0;
        $payment->user_id = Auth::id();

        app(PaymentInterface::class)->createOrUpdate($payment);

        event(new OrderPaymentConfirmedEvent($order, Auth::user()));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('order_confirm_payment')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'order_confirm_payment',
                $order->user->email ?: $order->address->email
            );
        }

        app(OrderHistoryInterface::class)->createOrUpdate([
            'action'      => 'confirm_payment',
            'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                'money' => format_price($order->amount),
            ]),
            'order_id'    => $order->id,
            'user_id'     => Auth::id(),
        ]);

        return true;
    }

    /**
     * @param Order $order
     *
     * @return Order
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function cancelOrder(Order $order): Order
    {
        $order->status = OrderStatusEnum::CANCELED;
        $order->is_confirmed = true;
        $order->save();

        event(new OrderCancelledEvent($order));

        foreach ($order->products as $orderProduct) {
            $product = $orderProduct->product;
            $product->quantity += $orderProduct->qty;
            $product->save();

            if ($product->is_variation) {
                $originalProduct = $product->original_product;

                if ($originalProduct->id != $product->id) {
                    $originalProduct->quantity += $orderProduct->qty;
                    $originalProduct->save();
                }
            }
        }

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('customer_cancel_order')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'customer_cancel_order',
                $order->user->email ?: $order->address->email
            );
        }

        return $order;
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function decreaseProductQuantity(Order $order): bool
    {
        foreach ($order->products as $orderProduct) {
            $product = app(ProductInterface::class)->findById($orderProduct->product_id);

            if ($product) {
                $product->quantity = $product->quantity >= $orderProduct->qty ? $product->quantity - $orderProduct->qty : 0;
                $product->save();
            }
        }

        return true;
    }
}
