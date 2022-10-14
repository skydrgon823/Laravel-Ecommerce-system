<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ShippingCodStatusEnum;
use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Models\StoreLocator;
use Botble\Ecommerce\Services\HandleShippingFeeService;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Models\Revenue;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Carbon\Carbon;
use DB;
use EcommerceHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MarketplaceHelper;
use Throwable;

class OrderEcommerceSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        OrderProduct::truncate();
        Shipment::truncate();
        Payment::truncate();
        OrderAddress::truncate();
        OrderHistory::truncate();
        ShipmentHistory::truncate();

        $isMarketplace = is_plugin_active('marketplace');
        $storeLocators = StoreLocator::all();
        $currency = get_application_currency();

        $shippingFeeService = app(HandleShippingFeeService::class);

        $products = Product::where('is_variation', 1)
                ->with([
                    'variationInfo',
                    'variationInfo.configurableProduct',
                ])
                ->get();

        if ($isMarketplace) {
            $customers = Customer::where('is_vendor', 0)->with(['addresses'])->get();
            $products->load(['variationInfo.configurableProduct.store']);
        } else {
            $customers = Customer::with(['addresses'])->get();
        }

        $total = 20;
        for ($i = 0; $i < $total; $i++) {
            $customer = $customers->random();
            $address = $customer->addresses->first();

            if (!$address) {
                continue;
            }

            $orderProducts = $products->random(rand(2, 4));

            $groupedProducts = $this->group($orderProducts);

            foreach ($groupedProducts as $grouped) {
                $taxAmount = 0;
                $subTotal = 0;
                $weight = 0;
                foreach ($grouped['products'] as &$product) {
                    $qty = rand(1, 3);
                    $product->qty = $qty;
                    $subTotal += $qty * $product->price;
                    $weight += $qty * $product->weight;
                    $product->tax_amount = $this->getTax($product);
                    $taxAmount += $product->tax_amount;
                }

                $shippingData = [
                    'address'     => $address->address,
                    'country'     => $address->country,
                    'state'       => $address->state,
                    'city'        => $address->city,
                    'weight'      => $weight ?: 0.1,
                    'order_total' => $subTotal,
                ];

                $shippingMethod = $shippingFeeService->execute($shippingData, ShippingMethodEnum::DEFAULT);

                $shippingAmount = Arr::get(Arr::first($shippingMethod), 'price', 0);
                $time = now()->subMinutes(($total - $i) * 120 * rand(1, 10));

                $order = [
                    'amount'          => $subTotal + $taxAmount + $shippingAmount,
                    'user_id'         => $customer->id,
                    'shipping_method' => ShippingMethodEnum::DEFAULT,
                    'shipping_option' => 1,
                    'shipping_amount' => $shippingAmount,
                    'tax_amount'      => $taxAmount,
                    'sub_total'       => $subTotal,
                    'coupon_code'     => null,
                    'discount_amount' => 0,
                    'status'          => OrderStatusEnum::PENDING,
                    'is_finished'     => true,
                    'token'           => Str::random(29),
                    'created_at'      => $time,
                    'updated_at'      => $time,
                    'is_confirmed'    => true,
                ];

                $order = Order::create($order);
                if ($isMarketplace) {
                    $order->store_id = $grouped['store']->id ?: 0;
                }

                foreach ($grouped['products'] as $product) {
                    $data = [
                        'order_id'     => $order->id,
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'qty'          => $product->qty,
                        'weight'       => $product->weight * $product->qty,
                        'price'        => $product->price ?: 1,
                        'tax_amount'   => $product->tax_amount,
                        'options'      => [],
                    ];
                    OrderProduct::create($data);
                }

                OrderAddress::create([
                    'name'     => $address->name,
                    'phone'    => $address->phone,
                    'email'    => $address->email,
                    'country'  => $address->country,
                    'state'    => $address->state,
                    'city'     => $address->city,
                    'address'  => $address->address,
                    'zip_code' => $address->zip_code,
                    'order_id' => $order->id,
                ]);

                OrderHistory::create([
                    'action'      => 'create_order_from_seeder',
                    'description' => __('Order is created from the checkout page'),
                    'order_id'    => $order->id,
                    'created_at'  => $time,
                    'updated_at'  => $time
                ]);

                OrderHistory::create([
                    'action'      => 'confirm_order',
                    'description' => trans('plugins/ecommerce::order.order_was_verified_by'),
                    'order_id'    => $order->id,
                    'user_id'     => 0,
                    'created_at'  => $time,
                    'updated_at'  => $time
                ]);

                $paymentStatus = PaymentStatusEnum::COMPLETED;
                $paymentMethod = Arr::random(PaymentMethodEnum::values());

                if (in_array($paymentMethod, [PaymentMethodEnum::COD, PaymentMethodEnum::BANK_TRANSFER])) {
                    $paymentStatus = PaymentStatusEnum::PENDING;
                }

                $payment = Payment::create([
                    'amount'          => $order->amount,
                    'currency'        => $currency->title,
                    'payment_channel' => $paymentMethod,
                    'status'          => $paymentStatus,
                    'payment_type'    => 'confirm',
                    'order_id'        => $order->id,
                    'charge_id'       => Str::upper(Str::random(10)),
                    'user_id'         => 0,
                    'customer_id'     => $customer->id,
                    'customer_type'   => Customer::class,
                ]);

                if ($paymentStatus == PaymentStatusEnum::COMPLETED) {
                    OrderHistory::create([
                        'action'      => 'confirm_payment',
                        'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                            'money' => format_price($order->amount),
                        ]),
                        'order_id'    => $order->id,
                        'user_id'     => 0,
                    ]);
                }

                $order->payment_id = $payment->id;
                $order->save();

                $shipmentStatus = Arr::random([ShippingStatusEnum::APPROVED, ShippingStatusEnum::DELIVERED]);
                $codAmount = 0;
                $codStatus = ShippingCodStatusEnum::COMPLETED;

                if ($paymentMethod == PaymentMethodEnum::COD) {
                    $codAmount = $order->amount;
                }

                if ($codAmount) {
                    $codStatus = ShippingCodStatusEnum::PENDING;
                }

                $shipment = Shipment::create([
                    'status'                => $shipmentStatus,
                    'order_id'              => $order->id,
                    'weight'                => $weight,
                    'note'                  => '',
                    'cod_amount'            => $codAmount,
                    'cod_status'            => $codStatus,
                    'price'                 => $order->shipping_amount,
                    'store_id'              => $storeLocators->count() > 1 ? $storeLocators->random(1)->id : 0,
                    'tracking_id'           => 'JJD00' . rand(1111111, 99999999),
                    'shipping_company_name' => Arr::random(['DHL', 'AliExpress', 'GHN', 'FastShipping']),
                    'tracking_link'         => 'https://mydhl.express.dhl/us/en/tracking.html#/track-by-reference',
                    'estimate_date_shipped' => now()->addDays(rand(1, 10)),
                    'date_shipped'          => $shipmentStatus == ShippingStatusEnum::DELIVERED ? now() : null,
                ]);

                OrderHistory::create([
                    'action'      => 'create_shipment',
                    'description' => __('Created shipment for order'),
                    'order_id'    => $order->id,
                    'user_id'     => 0,
                ]);

                ShipmentHistory::create([
                    'action'      => 'create_from_order',
                    'description' => trans('plugins/ecommerce::order.shipping_was_created_from'),
                    'shipment_id' => $shipment->id,
                    'order_id'    => $order->id,
                    'user_id'     => 0,
                    'created_at'  => $time,
                    'updated_at'  => $time
                ]);

                ShipmentHistory::create([
                    'action'      => 'update_status',
                    'description' => trans('plugins/ecommerce::shipping.changed_shipping_status', [
                        'status' => ShippingStatusEnum::getLabel(ShippingStatusEnum::APPROVED),
                    ]),
                    'shipment_id' => $shipment->id,
                    'order_id'    => $order->id,
                    'user_id'     => 0,
                    'created_at'  => now()->subMinutes(($total - $i) * 120),
                ]);

                if ($shipmentStatus == ShippingStatusEnum::DELIVERED) {
                    $order->status = OrderStatusEnum::COMPLETED;
                    $order->completed_at = Carbon::now();
                    $order->save();

                    OrderHistory::create([
                        'action'      => 'update_status',
                        'description' => trans('plugins/ecommerce::shipping.changed_shipping_status', [
                            'status' => ShippingStatusEnum::getLabel($shipmentStatus),
                        ]),
                        'order_id'    => $order->id,
                        'user_id'     => 0,
                    ]);

                    if ($payment->status !== PaymentStatusEnum::COMPLETED && $paymentMethod == PaymentMethodEnum::COD) {
                        $shipment->cod_status = ShippingCodStatusEnum::COMPLETED;
                        $shipment->save();

                        ShipmentHistory::create([
                            'action'      => 'update_cod_status',
                            'description' => trans('plugins/ecommerce::shipping.updated_cod_status_by', [
                                'status' => ShippingCodStatusEnum::getLabel(ShippingCodStatusEnum::COMPLETED),
                            ]),
                            'shipment_id' => $shipment->id,
                            'order_id'    => $order->id,
                            'user_id'     => 0,
                        ]);
                    }

                    ShipmentHistory::create([
                        'action'      => 'update_status',
                        'description' => trans('plugins/ecommerce::shipping.changed_shipping_status', [
                            'status' => ShippingStatusEnum::getLabel($shipmentStatus),
                        ]),
                        'shipment_id' => $shipment->id,
                        'order_id'    => $order->id,
                        'user_id'     => 0,
                    ]);
                }
            }
        }

        $orders = Order::with(['payment', 'shipment'])->get();
        if ($isMarketplace) {
            Revenue::truncate();
            $orders->load(['store', 'store.customer']);
        }
        foreach ($orders as $order) {
            if ($order->payment->status == PaymentStatusEnum::COMPLETED && $order->shipment->status == ShippingStatusEnum::DELIVERED) {
                $order->status = OrderStatusEnum::COMPLETED;
                $order->completed_at = Carbon::now();
                $order->save();

                OrderHistory::create([
                    'action'      => 'update_status',
                    'description' => trans('plugins/ecommerce::shipping.order_confirmed_by'),
                    'order_id'    => $order->id,
                    'user_id'     => 0,
                ]);

                if ($isMarketplace && $order->store->id && $order->store->customer->id) {
                    $customer = $order->store->customer;
                    $vendorInfo = $customer->vendorInfo;

                    if ($vendorInfo->id) {
                        $feePercentage = MarketplaceHelper::getSetting('fee_per_order', 0);

                        $fee = $order->amount * ($feePercentage / 100);
                        $amount = $order->amount - $fee;
                        $currentBalance = $customer->balance;

                        $amountByCurrency = $amount;
                        $time = now()->subMinutes(($order->id + 1) * 120 * rand(1, 10));

                        $data = [
                            'sub_amount'      => $order->amount,
                            'fee'             => $fee,
                            'amount'          => $amount,
                            'currency'        => get_application_currency()->title,
                            'current_balance' => $currentBalance,
                            'customer_id'     => $customer->getKey(),
                            'created_at'      => $time,
                            'updated_at'      => $time,
                        ];
                        DB::beginTransaction();
                        try {
                            Revenue::create(array_merge([
                                'order_id' => $order->id,
                            ], $data));

                            $vendorInfo->total_revenue += $amountByCurrency;

                            $vendorInfo->balance += $amountByCurrency;
                            $vendorInfo->total_fee += $fee;
                            $vendorInfo->save();
                            DB::commit();
                        } catch (Throwable $th) {
                            DB::rollBack();
                            throw $th;
                        }
                    }
                }
            }
        }

        if (!$isMarketplace) {
            return;
        }

        Withdrawal::truncate();
        $vendors = Customer::where('is_vendor', 1)->get();
        $fee = MarketplaceHelper::getSetting('fee_withdrawal', 0);
        foreach ($vendors as $vendor) {
            $vendorInfo = $vendor->vendorInfo;
            $rand = rand(1, 3);
            for ($i = 0; $i <= $rand; $i++) {
                $amount = rand(1, (int) ($vendorInfo->balance / 3) - 10);

                if ($amount - $fee > 0) {
                    Withdrawal::create([
                        'fee'             => $fee,
                        'amount'          => $amount,
                        'customer_id'     => $vendor->getKey(),
                        'currency'        => $currency->title,
                        'bank_info'       => $vendorInfo->bank_info,
                        'description'     => '',
                        'current_balance' => $vendorInfo->balance > 0 ? $vendorInfo->balance : 0,
                        'status'          => Arr::random([
                            WithdrawalStatusEnum::PENDING,
                            WithdrawalStatusEnum::COMPLETED,
                            WithdrawalStatusEnum::PROCESSING,
                        ]),
                    ]);

                    $vendorInfo->balance -= $amount + $fee;
                    $vendorInfo->save();
                }
            }
        }
    }

    /**
     * Group products by store
     *
     * @param Collection $products
     * @return array|\Illuminate\Support\Collection
     */
    public function group($products)
    {
        $groupedProducts = collect([]);
        foreach ($products as $product) {
            $storeId = $product->original_product && $product->original_product->store_id ? $product->original_product->store_id : 0;
            if (!Arr::has($groupedProducts, $storeId)) {
                $groupedProducts[$storeId] = collect([
                    'store'    => $product->original_product->store,
                    'products' => collect([$product]),
                ]);
            } else {
                $groupedProducts[$storeId]['products'][] = $product;
            }
        }

        return $groupedProducts;
    }

    /**
     * Get tax by product
     *
     * @param Product $product
     * @return int
     */
    public function getTax(Product $product)
    {
        if (!EcommerceHelper::isTaxEnabled()) {
            return 0;
        }

        return $product->price * $product->tax->percentage / 100;
    }
}
