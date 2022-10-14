<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderAddressTypeEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Http\Requests\ApplyCouponRequest;
use Botble\Ecommerce\Http\Requests\CheckoutRequest;
use Botble\Ecommerce\Http\Requests\SaveCheckoutInformationRequest;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\TaxInterface;
use Botble\Ecommerce\Services\Footprints\FootprinterInterface;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyPromotionsService;
use Botble\Ecommerce\Services\HandleRemoveCouponService;
use Botble\Ecommerce\Services\HandleShippingFeeService;
use Botble\Payment\Supports\PaymentHelper;
use Carbon\Carbon;
use Cart;
use EcommerceHelper;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use OptimizerHelper;
use OrderHelper;
use Theme;
use Validator;

class PublicCheckoutController
{
    /**
     * @var TaxInterface
     */
    protected $taxRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var OrderProductInterface
     */
    protected $orderProductRepository;

    /**
     * @var OrderAddressInterface
     */
    protected $orderAddressRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var ShippingInterface
     */
    protected $shippingRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var DiscountInterface
     */
    protected $discountRepository;

    /**
     * PublicCheckoutController constructor.
     * @param TaxInterface $taxRepository
     * @param OrderInterface $orderRepository
     * @param OrderProductInterface $orderProductRepository
     * @param OrderAddressInterface $orderAddressRepository
     * @param AddressInterface $addressRepository
     * @param CustomerInterface $customerRepository
     * @param ShippingInterface $shippingRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param ProductInterface $productRepository
     * @param DiscountInterface $discountRepository
     */
    public function __construct(
        TaxInterface          $taxRepository,
        OrderInterface        $orderRepository,
        OrderProductInterface $orderProductRepository,
        OrderAddressInterface $orderAddressRepository,
        AddressInterface      $addressRepository,
        CustomerInterface     $customerRepository,
        ShippingInterface     $shippingRepository,
        OrderHistoryInterface $orderHistoryRepository,
        ProductInterface      $productRepository,
        DiscountInterface     $discountRepository
    ) {
        $this->taxRepository = $taxRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->shippingRepository = $shippingRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;

        OptimizerHelper::disable();
    }

    /**
     * @param string $token
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param HandleShippingFeeService $shippingFeeService
     * @param HandleApplyCouponService $applyCouponService
     * @param HandleRemoveCouponService $removeCouponService
     * @param HandleApplyPromotionsService $applyPromotionsService
     * @return BaseHttpResponse|Application|Factory|\Illuminate\Contracts\View\View
     * @throws FileNotFoundException|Exception
     */
    public function getCheckout(
        $token,
        Request $request,
        BaseHttpResponse $response,
        HandleShippingFeeService $shippingFeeService,
        HandleApplyCouponService $applyCouponService,
        HandleRemoveCouponService $removeCouponService,
        HandleApplyPromotionsService $applyPromotionsService
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        if (!EcommerceHelper::isEnabledGuestCheckout() && !auth('customer')->check()) {
            return $response->setNextUrl(route('customer.login'));
        }

        if ($token !== session('tracked_start_checkout')) {
            $order = $this->orderRepository->getFirstBy(['token' => $token, 'is_finished' => false]);

            if (!$order) {
                return $response->setNextUrl(route('public.index'));
            }
        }

        if (!$request->session()->has('error_msg') && $request->input('error') == 1 && $request->input('error_type') == 'payment') {
            $request->session()->flash('error_msg', __('Payment failed!'));
        }

        $sessionCheckoutData = OrderHelper::getOrderSessionData($token);

        //---------------------------------- Optimize
        [$products, $weight] = $this->getProductsInCart();
        if (!$products->count()) {
            return $response->setNextUrl(route('public.cart'));
        }

        foreach ($products as $product) {
            if ($product->isOutOfStock()) {
                return $response
                    ->setError()
                    ->setNextUrl(route('public.cart'))
                    ->setMessage(__('Product :product is out of stock!', ['product' => $product->original_product->name]));
            }
        }

        $digitalProducts = EcommerceHelper::countDigitalProducts($products);
        if ($digitalProducts && !auth('customer')->check()) {
            return $response
                ->setError()
                ->setNextUrl(route('customer.login'))
                ->setMessage(__('Your shopping cart has digital product(s), so you need to sign in to continue!'));
        }

        $sessionCheckoutData = $this->processOrderData($token, $sessionCheckoutData, $request);

        if (is_plugin_active('marketplace')) {
            [
                $sessionCheckoutData,
                $shipping,
                $defaultShippingMethod,
                $defaultShippingOption,
                $shippingAmount,
                $promotionDiscountAmount,
                $couponDiscountAmount,
            ] = apply_filters(PROCESS_CHECKOUT_ORDER_DATA_ECOMMERCE, $products, $token, $sessionCheckoutData, $request);
        } else {
            $promotionDiscountAmount = $applyPromotionsService->execute($token);

            $sessionCheckoutData['promotion_discount_amount'] = $promotionDiscountAmount;

            $couponDiscountAmount = 0;
            if (session()->has('applied_coupon_code')) {
                $couponDiscountAmount = Arr::get($sessionCheckoutData, 'coupon_discount_amount', 0);
            }

            $orderTotal = Cart::instance('cart')->rawTotal() - $promotionDiscountAmount;
            $orderTotal = max($orderTotal, 0);

            $shippingData = [
                'address'     => Arr::get($sessionCheckoutData, 'address'),
                'state'       => Arr::get($sessionCheckoutData, 'state'),
                'city'        => Arr::get($sessionCheckoutData, 'city'),
                'weight'      => $weight,
                'order_total' => $orderTotal,
            ];

            if (EcommerceHelper::isUsingInMultipleCountries()) {
                $shippingData['country'] = Arr::get($sessionCheckoutData, 'country');
            } else {
                $shippingData['country'] = EcommerceHelper::getFirstCountryId();
            }

            $shipping = $shippingFeeService->execute($shippingData);

            foreach ($shipping as $key => &$shipItem) {
                if (get_shipping_setting('free_ship', $key)) {
                    foreach ($shipItem as &$subShippingItem) {
                        Arr::set($subShippingItem, 'price', 0);
                    }
                }
            }

            $defaultShippingMethod = $request->input(
                'shipping_method',
                old(
                    'shipping_method',
                    Arr::get($sessionCheckoutData, 'shipping_method', Arr::first(array_keys($shipping)))
                )
            );

            $defaultShippingOption = null;
            if (!empty($shipping)) {
                $defaultShippingOption = Arr::first(array_keys(Arr::first($shipping)));
                $defaultShippingOption = $request->input(
                    'shipping_option',
                    old('shipping_option', Arr::get($sessionCheckoutData, 'shipping_option', $defaultShippingOption))
                );
            }
            $shippingAmount = Arr::get($shipping, $defaultShippingMethod . '.' . $defaultShippingOption . '.price', 0);

            Arr::set($sessionCheckoutData, 'shipping_method', $defaultShippingMethod);
            Arr::set($sessionCheckoutData, 'shipping_option', $defaultShippingOption);
            Arr::set($sessionCheckoutData, 'shipping_amount', $shippingAmount);

            OrderHelper::setOrderSessionData($token, $sessionCheckoutData);

            if (session()->has('applied_coupon_code')) {
                if (!$request->input('applied_coupon')) {
                    $discount = $applyCouponService->getCouponData(
                        session('applied_coupon_code'),
                        $sessionCheckoutData
                    );
                    if (empty($discount)) {
                        $removeCouponService->execute();
                    } else {
                        $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                    }
                } else {
                    $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                }
            }

            $sessionCheckoutData['is_available_shipping'] = EcommerceHelper::isAvailableShipping($products);

            if (!$sessionCheckoutData['is_available_shipping']) {
                $shippingAmount = 0;
            }
        }

        $data = compact(
            'token',
            'shipping',
            'defaultShippingMethod',
            'defaultShippingOption',
            'shippingAmount',
            'promotionDiscountAmount',
            'couponDiscountAmount',
            'sessionCheckoutData',
            'products',
        );

        $checkoutView = Theme::getThemeNamespace() . '::views.ecommerce.orders.checkout';

        if (view()->exists($checkoutView)) {
            return view($checkoutView, $data);
        }

        return view('plugins/ecommerce::orders.checkout', $data);
    }

    /**
     * @return array
     */
    protected function getProductsInCart(): array
    {
        $products = Cart::instance('cart')->products();
        $weight = Cart::instance('cart')->weight();

        return [$products, $weight];
    }

    /**
     * @param string $token
     * @param array $sessionData
     * @param Request $request
     * @param bool $finished
     * @return array
     * @throws Exception
     */
    protected function processOrderData(string $token, array $sessionData, Request $request, bool $finished = false): array
    {
        if ($request->has('billing_address_same_as_shipping_address')) {
            $sessionData['billing_address_same_as_shipping_address'] = $request->input('billing_address_same_as_shipping_address');
        }

        if ($request->has('billing_address')) {
            $sessionData['billing_address'] = $request->input('billing_address');
        }

        if ($request->input('address', [])) {
            if (!isset($sessionData['created_account']) && $request->input('create_account') == 1) {
                $validator = Validator::make($request->input(), [
                    'password'              => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                    'address.email'         => 'required|max:60|min:6|email|unique:ec_customers,email',
                    'address.name'          => 'required|min:3|max:120',
                ]);

                if (!$validator->fails()) {
                    $customer = $this->customerRepository->createOrUpdate([
                        'name'     => BaseHelper::clean($request->input('address.name')),
                        'email'    => BaseHelper::clean($request->input('address.email')),
                        'phone'    => BaseHelper::clean($request->input('address.phone')),
                        'password' => bcrypt($request->input('password')),
                    ]);

                    auth('customer')->attempt([
                        'email'    => $request->input('address.email'),
                        'password' => $request->input('password'),
                    ], true);

                    event(new Registered($customer));

                    $sessionData['created_account'] = true;

                    $address = $this->addressRepository->createOrUpdate($request->input('address') + [
                            'customer_id' => $customer->id,
                            'is_default'  => true,
                        ]);

                    $request->merge(['address.address_id' => $address->id]);
                    $sessionData['address_id'] = $address->id;
                }
            }

            if ($finished && auth('customer')->check() && (auth('customer')->user()->addresses()->count() == 0 || $request->input('address.address_id') == 'new')) {
                $address = $this->addressRepository->createOrUpdate($request->input('address', []) +
                    ['customer_id' => auth('customer')->id(), 'is_default' => auth('customer')->user()->addresses()->count() == 0]);

                $request->merge(['address.address_id' => $address->id]);
                $sessionData['address_id'] = $address->id;
            }
        }

        $address = null;

        if ($request->input('address.address_id') && $request->input('address.address_id') !== 'new') {
            $address = $this->addressRepository->findById($request->input('address.address_id'));
            if (!empty($address)) {
                $sessionData['address_id'] = $address->id;
                $sessionData['created_order_address_id'] = $address->id;
            }
        } elseif (auth('customer')->check() && !Arr::get($sessionData, 'address_id')) {
            $address = $this->addressRepository->getFirstBy([
                'customer_id' => auth('customer')->id(),
                'is_default'  => true,
            ]);

            if ($address) {
                $sessionData['address_id'] = $address->id;
            }
        }

        if (Arr::get($sessionData, 'address_id') && Arr::get($sessionData, 'address_id') !== 'new') {
            $address = $this->addressRepository->findById(Arr::get($sessionData, 'address_id'));
        }

        $addressData = [
            'billing_address_same_as_shipping_address' => Arr::get($sessionData, 'billing_address_same_as_shipping_address', true),
            'billing_address'                          => Arr::get($sessionData, 'billing_address', []),
        ];

        if (!empty($address)) {
            $addressData = [
                'name'       => $address->name,
                'phone'      => $address->phone,
                'email'      => $address->email,
                'country'    => $address->country,
                'state'      => $address->state,
                'city'       => $address->city,
                'address'    => $address->address,
                'zip_code'   => $address->zip_code,
                'address_id' => $address->id,
            ];
        } elseif ((array)$request->input('address', [])) {
            $addressData = (array)$request->input('address', []);
        }

        foreach ($addressData as $key => $addressItem) {
            if (!is_string($addressItem)) {
                continue;
            }

            $addressData[$key] = BaseHelper::clean($addressItem);
        }

        if ($addressData && !empty($addressData['name']) && (EcommerceHelper::isPhoneFieldOptionalAtCheckout() || !empty($addressData['phone'])) && !empty($addressData['address'])) {
            $addressData['billing_address_same_as_shipping_address'] = Arr::get($sessionData, 'billing_address_same_as_shipping_address', true);
            $addressData['billing_address'] = Arr::get($sessionData, 'billing_address');
        }

        $sessionData = array_merge($sessionData, $addressData);

        if (is_plugin_active('marketplace')) {
            $products = Cart::instance('cart')->products();

            $sessionData = apply_filters(
                HANDLE_PROCESS_ORDER_DATA_ECOMMERCE,
                $products,
                $token,
                $sessionData,
                $request
            );

            OrderHelper::setOrderSessionData($token, $sessionData);

            return $sessionData;
        }

        if (!isset($sessionData['created_order'])) {
            $currentUserId = 0;
            if (auth('customer')->check()) {
                $currentUserId = auth('customer')->id();
            }

            $request->merge([
                'amount'          => Cart::instance('cart')->rawTotal(),
                'user_id'         => $currentUserId,
                'shipping_method' => $request->input('shipping_method', ShippingMethodEnum::DEFAULT),
                'shipping_option' => $request->input('shipping_option'),
                'shipping_amount' => 0,
                'tax_amount'      => Cart::instance('cart')->rawTax(),
                'sub_total'       => Cart::instance('cart')->rawSubTotal(),
                'coupon_code'     => session()->get('applied_coupon_code'),
                'discount_amount' => 0,
                'status'          => OrderStatusEnum::PENDING,
                'is_finished'     => false,
                'token'           => $token,
            ]);

            $order = $this->orderRepository->getFirstBy(compact('token'));

            $order = $this->createOrderFromData($request->input(), $order);

            $sessionData['created_order'] = true;
            $sessionData['created_order_id'] = $order->id;
        }

        if (!empty($address)) {
            $addressData['order_id'] = $sessionData['created_order_id'];
        } elseif ((array)$request->input('address', [])) {
            $addressData = array_merge(
                ['order_id' => $sessionData['created_order_id']],
                (array)$request->input('address', [])
            );
        }

        if ($addressData && !empty($addressData['name']) && (EcommerceHelper::isPhoneFieldOptionalAtCheckout() || !empty($addressData['phone'])) && !empty($addressData['address'])) {
            if (!isset($sessionData['created_order_address'])) {
                $createdOrderAddress = $this->createOrderAddress(
                    $addressData,
                    Arr::get($addressData, 'created_order_id')
                );
                if ($createdOrderAddress) {
                    $sessionData['created_order_address'] = true;
                    $sessionData['created_order_address_id'] = $createdOrderAddress->id;
                }
            } elseif (!empty($sessionData['created_order_id'])) {
                $this->createOrderAddress($addressData, $sessionData['created_order_id']);
            }
        }

        if (!isset($sessionData['created_order_product'])) {
            $weight = 0;
            foreach (Cart::instance('cart')->content() as $cartItem) {
                $product = $this->productRepository->findById($cartItem->id);
                if ($product) {
                    if ($product->weight) {
                        $weight += $product->weight * $cartItem->qty;
                    }
                }
            }

            $weight = EcommerceHelper::validateOrderWeight($weight);

            $this->orderProductRepository->deleteBy(['order_id' => $sessionData['created_order_id']]);

            foreach (Cart::instance('cart')->content() as $cartItem) {
                $product = $this->productRepository->findById($cartItem->id);

                $data = [
                    'order_id'     => $sessionData['created_order_id'],
                    'product_id'   => $cartItem->id,
                    'product_name' => $cartItem->name,
                    'qty'          => $cartItem->qty,
                    'weight'       => $weight,
                    'price'        => $cartItem->price,
                    'tax_amount'   => $cartItem->tax,
                    'options'      => [],
                    'product_type' => $product ? $product->product_type : null,
                ];

                if ($cartItem->options->extras) {
                    $data['options'] = $cartItem->options->extras;
                }

                $this->orderProductRepository->create($data);
            }

            $sessionData['created_order_product'] = Cart::instance('cart')->getLastUpdatedAt();
        }

        OrderHelper::setOrderSessionData($token, $sessionData);

        return $sessionData;
    }

    /**
     * @param array $data
     * @param int|null $orderId
     * @return false|Model|mixed
     */
    protected function createOrderAddress(array $data, ?int $orderId = null)
    {
        if ($orderId) {
            $this->storeOrderBillingAddress($data, $orderId);

            return $this->orderAddressRepository->createOrUpdate($data, ['order_id' => $orderId, 'type' => OrderAddressTypeEnum::SHIPPING]);
        }

        $validator = Validator::make($data, EcommerceHelper::getCustomerAddressValidationRules());

        if ($validator->fails()) {
            return false;
        }

        $this->storeOrderBillingAddress($data);

        return $this->orderAddressRepository->create($data);
    }

    /**
     * @param array $data
     * @param int|null $orderId
     * @return void
     */
    protected function storeOrderBillingAddress(array $data, ?int $orderId = null)
    {
        if (isset($data['billing_address_same_as_shipping_address']) && !$data['billing_address_same_as_shipping_address']) {
            $billingAddressData = $data['billing_address'];
            $billingAddressData['order_id'] = $orderId ?: Arr::get($data, 'order_id');
            $billingAddressData['type'] = OrderAddressTypeEnum::BILLING;

            $this->orderAddressRepository->createOrUpdate($billingAddressData, ['order_id' => $orderId, 'type' => OrderAddressTypeEnum::BILLING]);
        } else {
            $this->orderAddressRepository->deleteBy([
                'order_id' => $orderId,
                'type'     => OrderAddressTypeEnum::BILLING,
            ]);
        }
    }

    /**
     * @param string $token
     * @param SaveCheckoutInformationRequest $request
     * @param BaseHttpResponse $response
     * @param HandleApplyCouponService $applyCouponService
     * @param HandleRemoveCouponService $removeCouponService
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postSaveInformation(
        $token,
        SaveCheckoutInformationRequest $request,
        BaseHttpResponse $response,
        HandleApplyCouponService $applyCouponService,
        HandleRemoveCouponService $removeCouponService
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        if ($token !== session('tracked_start_checkout')) {
            $order = $this->orderRepository->getFirstBy(['token' => $token, 'is_finished' => false]);

            if (!$order) {
                return $response->setNextUrl(route('public.index'));
            }
        }

        if (is_plugin_active('marketplace')) {
            $sessionData = array_merge(OrderHelper::getOrderSessionData($token), $request->input('address'));
            $sessionData = apply_filters(
                PROCESS_POST_SAVE_INFORMATION_CHECKOUT_ECOMMERCE,
                $sessionData,
                $request,
                $token
            );
        } else {
            $sessionData = array_merge(OrderHelper::getOrderSessionData($token), $request->input('address'));
            OrderHelper::setOrderSessionData($token, $sessionData);
            if (session()->has('applied_coupon_code')) {
                $discount = $applyCouponService->getCouponData(session('applied_coupon_code'), $sessionData);
                if (empty($discount)) {
                    $removeCouponService->execute();
                }
            }
        }

        session()->put('selected_payment_method', $request->input('payment_method'));

        $sessionData = $this->processOrderData($token, $sessionData, $request);

        return $response->setData($sessionData);
    }

    /**
     * @param string $token
     * @param CheckoutRequest $request
     * @param BaseHttpResponse $response
     * @param HandleShippingFeeService $shippingFeeService
     * @param HandleApplyCouponService $applyCouponService
     * @param HandleRemoveCouponService $removeCouponService
     * @param HandleApplyPromotionsService $handleApplyPromotionsService
     * @return BaseHttpResponse|Application|RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws Exception
     */
    public function postCheckout(
        $token,
        CheckoutRequest $request,
        BaseHttpResponse $response,
        HandleShippingFeeService $shippingFeeService,
        HandleApplyCouponService $applyCouponService,
        HandleRemoveCouponService $removeCouponService,
        HandleApplyPromotionsService $handleApplyPromotionsService
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        if (!EcommerceHelper::isEnabledGuestCheckout() && !auth('customer')->check()) {
            return $response->setNextUrl(route('customer.login'));
        }

        if (!Cart::instance('cart')->count()) {
            return $response
                ->setError()
                ->setMessage(__('No products in cart'));
        }

        $products = Cart::instance('cart')->products();

        $digitalProducts = EcommerceHelper::countDigitalProducts($products);
        if ($digitalProducts && !auth('customer')->check()) {
            return $response
                ->setError()
                ->setNextUrl(route('customer.login'))
                ->setMessage(__('Your shopping cart has digital product(s), so you need to sign in to continue!'));
        }

        if (EcommerceHelper::getMinimumOrderAmount() > Cart::instance('cart')->rawSubTotal()) {
            return $response
                ->setError()
                ->setMessage(__('Minimum order amount is :amount, you need to buy more :more to place an order!', [
                    'amount' => format_price(EcommerceHelper::getMinimumOrderAmount()),
                    'more'   => format_price(EcommerceHelper::getMinimumOrderAmount() - Cart::instance('cart')
                            ->rawSubTotal()),
                ]));
        }

        $sessionData = OrderHelper::getOrderSessionData($token);

        $sessionData = $this->processOrderData($token, $sessionData, $request, true);

        if (is_plugin_active('marketplace')) {
            foreach ($products as $product) {
                if ($product->isOutOfStock()) {
                    return $response
                        ->setError()
                        ->setMessage(__('Product :product is out of stock!', ['product' => $product->original_product->name]));
                }
            }

            return apply_filters(
                HANDLE_PROCESS_POST_CHECKOUT_ORDER_DATA_ECOMMERCE,
                $products,
                $request,
                $token,
                $sessionData,
                $response
            );
        }

        $weight = 0;
        foreach (Cart::instance('cart')->content() as $cartItem) {
            $product = $this->productRepository->findById($cartItem->id);
            if ($product) {
                if ($product->isOutOfStock()) {
                    return $response
                        ->setError()
                        ->setMessage(__('Product :product is out of stock!', ['product' => $product->original_product->name]));
                }

                if ($product->weight) {
                    $weight += $product->weight * $cartItem->qty;
                }
            }
        }

        $isAvailableShipping = EcommerceHelper::isAvailableShipping($products);

        $hasShippingMethod = $request->has('shipping_method');
        $shippingMethodInput = $request->input('shipping_method', ShippingMethodEnum::DEFAULT);
        if ($isAvailableShipping) {
            $weight = EcommerceHelper::validateOrderWeight($weight);
        } else {
            $weight = 0;
            $hasShippingMethod = false;
        }

        $promotionDiscountAmount = $handleApplyPromotionsService->execute($token);
        $couponDiscountAmount = Arr::get($sessionData, 'coupon_discount_amount');

        $shippingAmount = 0;

        if ($hasShippingMethod && !get_shipping_setting('free_ship', $shippingMethodInput)) {
            $shippingData = [
                'address'     => Arr::get($sessionData, 'address'),
                'country'     => Arr::get($sessionData, 'country'),
                'state'       => Arr::get($sessionData, 'state'),
                'city'        => Arr::get($sessionData, 'city'),
                'weight'      => $weight,
                'order_total' => Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount,
            ];

            $shippingMethod = $shippingFeeService->execute(
                $shippingData,
                $shippingMethodInput,
                $request->input('shipping_option')
            );

            $shippingAmount = Arr::get(Arr::first($shippingMethod), 'price', 0);
        }

        if (session()->has('applied_coupon_code')) {
            $discount = $applyCouponService->getCouponData(session('applied_coupon_code'), $sessionData);
            if (empty($discount)) {
                $removeCouponService->execute();
            } else {
                $shippingAmount = Arr::get($sessionData, 'is_free_shipping') ? 0 : $shippingAmount;
            }
        }

        $currentUserId = 0;
        if (auth('customer')->check()) {
            $currentUserId = auth('customer')->id();
        }

        $amount = Cart::instance('cart')->rawTotal() + (float)$shippingAmount - $promotionDiscountAmount - $couponDiscountAmount;

        $request->merge([
            'amount'          => $amount ?: 0,
            'currency'        => $request->input('currency', strtoupper(get_application_currency()->title)),
            'user_id'         => $currentUserId,
            'shipping_method' => $hasShippingMethod ? $shippingMethodInput : '',
            'shipping_option' => $hasShippingMethod ? $request->input('shipping_option') : null,
            'shipping_amount' => (float)$shippingAmount,
            'tax_amount'      => Cart::instance('cart')->rawTax(),
            'sub_total'       => Cart::instance('cart')->rawSubTotal(),
            'coupon_code'     => session()->get('applied_coupon_code'),
            'discount_amount' => $promotionDiscountAmount + $couponDiscountAmount,
            'status'          => OrderStatusEnum::PENDING,
            'token'           => $token,
        ]);

        $order = $this->orderRepository->getFirstBy(compact('token'));

        $order = $this->createOrderFromData($request->input(), $order);

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'create_order_from_payment_page',
            'description' => __('Order was created from checkout page'),
            'order_id'    => $order->id,
        ]);

        $discount = $this->discountRepository
            ->getModel()
            ->where('code', session()->get('applied_coupon_code'))
            ->where('type', 'coupon')
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', Carbon::now());
            })
            ->first();

        if (!empty($discount)) {
            $discount->total_used++;
            $this->discountRepository->createOrUpdate($discount);
        }

        $this->orderProductRepository->deleteBy(['order_id' => $order->id]);

        foreach (Cart::instance('cart')->content() as $cartItem) {
            $product = $this->productRepository->findById($cartItem->id);

            $data = [
                'order_id'     => $order->id,
                'product_id'   => $cartItem->id,
                'product_name' => $cartItem->name,
                'qty'          => $cartItem->qty,
                'weight'       => $weight,
                'price'        => $cartItem->price,
                'tax_amount'   => $cartItem->tax,
                'options'      => [],
                'product_type' => $product ? $product->product_type : null,
            ];

            if ($cartItem->options->extras) {
                $data['options'] = $cartItem->options->extras;
            }

            $this->orderProductRepository->create($data);
        }

        $request->merge([
            'order_id' => $order->id,
        ]);

        $paymentData = [
            'error'     => false,
            'message'   => false,
            'amount'    => (float)format_price($order->amount, null, true),
            'currency'  => strtoupper(get_application_currency()->title),
            'type'      => $request->input('payment_method'),
            'charge_id' => null,
        ];

        $paymentData = apply_filters(FILTER_ECOMMERCE_PROCESS_PAYMENT, $paymentData, $request);

        if ($checkoutUrl = Arr::get($paymentData, 'checkoutUrl')) {
            return $response
                ->setError($paymentData['error'])
                ->setNextUrl($checkoutUrl)
                ->setData(['checkoutUrl' => $checkoutUrl])
                ->withInput()
                ->setMessage($paymentData['message']);
        }

        if ($paymentData['error'] || !$paymentData['charge_id']) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL($token))
                ->withInput()
                ->setMessage($paymentData['message'] ?: __('Checkout error!'));
        }

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL($token))
            ->setMessage(__('Checkout successfully!'));
    }

    /**
     * @param string $token
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|Application|Factory|RedirectResponse|View
     */
    public function getCheckoutSuccess($token, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        $order = $this->orderRepository->getFirstBy(['token' => $token], [], ['address', 'products']);

        if (!$order || session('tracked_start_checkout') && $token !== session('tracked_start_checkout')) {
            abort(404);
        }

        if (!$order->payment_id) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        }

        if (is_plugin_active('marketplace')) {
            return apply_filters(PROCESS_GET_CHECKOUT_SUCCESS_IN_ORDER, $token, $response);
        }

        event(new OrderPlacedEvent($order));

        $order->is_finished = true;
        $order->save();

        OrderHelper::decreaseProductQuantity($order);

        $products = collect([]);

        $productsIds = $order->products->pluck('product_id')->all();

        if (!empty($productsIds)) {
            $products = get_products([
                'condition' => [
                    ['ec_products.id', 'IN', $productsIds],
                ],
                'select'    => [
                    'ec_products.id',
                    'ec_products.images',
                    'ec_products.name',
                    'ec_products.price',
                    'ec_products.sale_price',
                    'ec_products.sale_type',
                    'ec_products.start_date',
                    'ec_products.end_date',
                    'ec_products.sku',
                    'ec_products.order',
                    'ec_products.created_at',
                    'ec_products.is_variation',
                ],
                'with'      => [
                    'variationProductAttributes',
                ],
            ]);
        }

        OrderHelper::clearSessions($token);

        return view('plugins/ecommerce::orders.thank-you', compact('order', 'products'));
    }

    /**
     * @param ApplyCouponRequest $request
     * @param HandleApplyCouponService $handleApplyCouponService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postApplyCoupon(
        ApplyCouponRequest       $request,
        HandleApplyCouponService $handleApplyCouponService,
        BaseHttpResponse         $response
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }
        $result = [
            'error'   => false,
            'message' => '',
        ];
        if (is_plugin_active('marketplace')) {
            $result = apply_filters(HANDLE_POST_APPLY_COUPON_CODE_ECOMMERCE, $result, $request);
        } else {
            $result = $handleApplyCouponService->execute($request->input('coupon_code'));
        }

        if ($result['error']) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage($result['message']);
        }

        $couponCode = $request->input('coupon_code');

        return $response
            ->setMessage(__('Applied coupon ":code" successfully!', ['code' => $couponCode]));
    }

    /**
     * @param Request $request
     * @param HandleRemoveCouponService $removeCouponService
     * @param BaseHttpResponse $response
     * @return array|BaseHttpResponse
     */
    public function postRemoveCoupon(
        Request                   $request,
        HandleRemoveCouponService $removeCouponService,
        BaseHttpResponse          $response
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        if (is_plugin_active('marketplace')) {
            $products = Cart::instance('cart')->products();
            $result = apply_filters(HANDLE_POST_REMOVE_COUPON_CODE_ECOMMERCE, $products, $request);
        } else {
            $result = $removeCouponService->execute();
        }

        if ($result['error']) {
            if ($request->ajax()) {
                return $result;
            }
            return $response
                ->setError()
                ->setData($result)
                ->setMessage($result['message']);
        }

        return $response
            ->setMessage(__('Removed coupon :code successfully!', ['code' => session('applied_coupon_code')]));
    }

    /**
     * @param string $token
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param HandleShippingFeeService $shippingFeeService
     * @param HandleApplyCouponService $applyCouponService
     * @param HandleRemoveCouponService $removeCouponService
     * @param HandleApplyPromotionsService $applyPromotionsService
     * @return BaseHttpResponse|Application|Factory|View
     * @throws Exception
     */
    public function getCheckoutRecover(
        $token,
        Request $request,
        BaseHttpResponse $response,
        HandleShippingFeeService $shippingFeeService,
        HandleApplyCouponService $applyCouponService,
        HandleRemoveCouponService $removeCouponService,
        HandleApplyPromotionsService $applyPromotionsService
    ) {
        if (!EcommerceHelper::isCartEnabled()) {
            abort(404);
        }

        if (!EcommerceHelper::isEnabledGuestCheckout() && !auth('customer')->check()) {
            return $response->setNextUrl(route('customer.login'));
        }

        if (is_plugin_active('marketplace')) {
            return apply_filters(PROCESS_GET_CHECKOUT_RECOVER_ECOMMERCE, $token, $request);
        }

        $order = $this->orderRepository
            ->getFirstBy([
                'token'       => $token,
                'is_finished' => false,
            ], [], ['products', 'address']);

        if (!$order) {
            abort(404);
        }

        if (session()->has('tracked_start_checkout') && session('tracked_start_checkout') == $token) {
            $sessionCheckoutData = OrderHelper::getOrderSessionData($token);
        } else {
            session(['tracked_start_checkout' => $token]);
            $sessionCheckoutData = [
                'name'            => $order->address->name,
                'email'           => $order->address->email,
                'phone'           => $order->address->phone,
                'address'         => $order->address->address,
                'country'         => $order->address->country,
                'state'           => $order->address->state,
                'city'            => $order->address->city,
                'zip_code'        => $order->address->zip_code,
                'shipping_method' => $order->shipping_method,
                'shipping_option' => $order->shipping_option,
                'shipping_amount' => $order->shipping_amount,
            ];
        }

        Cart::instance('cart')->destroy();
        foreach ($order->products as $orderProduct) {
            $request->merge(['qty' => $orderProduct->qty]);

            $product = $this->productRepository->findById($orderProduct->product_id);
            if ($product) {
                OrderHelper::handleAddCart($product, $request);
            }
        }

        [$products, $weight] = $this->getProductsInCart();

        $promotionDiscountAmount = $applyPromotionsService->execute($token);

        $sessionCheckoutData['promotion_discount_amount'] = $promotionDiscountAmount;

        $couponDiscountAmount = 0;
        if (session()->has('applied_coupon_code')) {
            $couponDiscountAmount = Arr::get($sessionCheckoutData, 'coupon_discount_amount', 0);
        }

        $orderTotal = Cart::instance('cart')->rawTotal() - $promotionDiscountAmount;
        $orderTotal = max($orderTotal, 0);

        $sessionCheckoutData = $this->processOrderData($token, $sessionCheckoutData, $request);

        $shippingData = [
            'address'     => Arr::get($sessionCheckoutData, 'address'),
            'state'       => Arr::get($sessionCheckoutData, 'state'),
            'city'        => Arr::get($sessionCheckoutData, 'city'),
            'zip_code'    => Arr::get($sessionCheckoutData, 'zip_code'),
            'weight'      => $weight,
            'order_total' => $orderTotal,
        ];

        if (EcommerceHelper::isUsingInMultipleCountries()) {
            $shippingData['country'] = Arr::get($sessionCheckoutData, 'country');
        } else {
            $shippingData['country'] = EcommerceHelper::getFirstCountryId();
        }

        $shipping = $shippingFeeService->execute($shippingData);

        foreach ($shipping as $key => &$shippingItem) {
            if (get_shipping_setting('free_ship', $key)) {
                foreach ($shippingItem as &$subShippingItem) {
                    Arr::set($subShippingItem, 'price', 0);
                }
            }
        }

        $defaultShippingMethod = $request->input(
            'shipping_method',
            old(
                'shipping_method',
                Arr::get($sessionCheckoutData, 'shipping_method', Arr::first(array_keys($shipping)))
            )
        );

        $defaultShippingOption = null;
        if (!empty($shipping)) {
            $defaultShippingOption = Arr::first(array_keys(Arr::first($shipping)));
            $defaultShippingOption = $request->input(
                'shipping_option',
                old('shipping_option', Arr::get($sessionCheckoutData, 'shipping_option') ?? $defaultShippingOption)
            );
        }
        $shippingAmount = Arr::get($shipping, $defaultShippingMethod . '.' . $defaultShippingOption . '.price', 0);

        Arr::set($sessionCheckoutData, 'shipping_method', $defaultShippingMethod);
        Arr::set($sessionCheckoutData, 'shipping_option', $defaultShippingOption);
        Arr::set($sessionCheckoutData, 'shipping_amount', $shippingAmount);
        OrderHelper::setOrderSessionData($token, $sessionCheckoutData);

        if (session()->has('applied_coupon_code')) {
            if (!$request->input('applied_coupon')) {
                $discount = $applyCouponService->getCouponData(session('applied_coupon_code'), $sessionCheckoutData);
                if (empty($discount)) {
                    $removeCouponService->execute();
                } else {
                    $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                }
            } else {
                $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
            }
        }

        return view('plugins/ecommerce::orders.checkout', compact(
            'token',
            'shipping',
            'defaultShippingMethod',
            'defaultShippingOption',
            'shippingAmount',
            'promotionDiscountAmount',
            'couponDiscountAmount',
            'sessionCheckoutData',
            'products'
        ));
    }

    /**
     * @param array $data
     * @param Order|null $order
     * @return false|Model
     */
    protected function createOrderFromData(array $data, ?Order $order): Order
    {
        $data['is_finished'] = false;

        if ($order) {
            $order->fill($data);
            $order = $this->orderRepository->createOrUpdate($order);
        } else {
            $order = $this->orderRepository->createOrUpdate($data);
        }

        if (!$order->referral()->count()) {
            $referrals = app(FootprinterInterface::class)->getFootprints();

            if ($referrals) {
                $order->referral()->create($referrals);
            }
        }

        return $order;
    }
}
