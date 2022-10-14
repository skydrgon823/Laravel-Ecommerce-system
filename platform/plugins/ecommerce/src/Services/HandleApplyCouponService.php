<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Carbon\Carbon;
use Cart;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use OrderHelper;

class HandleApplyCouponService
{
    /**
     * @var DiscountInterface
     */
    protected $discountRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * HandleApplyCouponService constructor.
     * @param DiscountInterface $discountRepository
     * @param ProductInterface $productRepository
     */
    public function __construct(DiscountInterface $discountRepository, ProductInterface $productRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $coupon
     * @param array $sessionData
     * @param array $cartData
     * @param string|null $prefix
     * @return array
     */
    public function execute(string $coupon, array $sessionData = [], array $cartData = [], ?string $prefix = ''): array
    {
        $token = OrderHelper::getOrderSessionToken();

        if (!$token) {
            $token = OrderHelper::getOrderSessionToken();
        }

        if (!$sessionData) {
            $sessionData = OrderHelper::getOrderSessionData($token);
        }

        $couponCode = trim($coupon);

        $discount = $this->getCouponData($couponCode, $sessionData);

        if (empty($discount)) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.invalid_coupon'),
            ];
        }

        if ($discount->target === 'customer') {
            $discountCustomers = $discount->customers()->pluck('customer_id')->all();
            if (!auth('customer')->check() ||
                !in_array(auth('customer')->id(), $discountCustomers)
            ) {
                return [
                    'error'   => true,
                    'message' => trans('plugins/ecommerce::discount.invalid_coupon'),
                ];
            }
        }

        if (!$discount->can_use_with_promotion && (float)Arr::get($sessionData, 'promotion_discount_amount')) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.cannot_use_same_time_with_other_discount_program'),
            ];
        }

        $cartItems = Arr::get($cartData, 'cartItems', Cart::instance('cart')->content());
        $rawTotal = Arr::get($cartData, 'rawTotal', Cart::instance('cart')->rawTotal());
        $countCart = Arr::get($cartData, 'countCart', Cart::instance('cart')->count());

        $couponDiscountAmount = 0;
        $isFreeShipping = false;
        $discountTypeOption = null;

        if ($discount->type_option == 'shipping') {
            $isFreeShipping = true;
            if ($prefix) {
                Arr::set($sessionData, $prefix . 'is_free_shipping', true);
            } else {
                $sessionData['is_free_shipping'] = true;
            }
            OrderHelper::setOrderSessionData($token, $sessionData);
        } elseif ($discount->type_option === 'amount' && $discount->discount_on === 'per-order') {
            $couponDiscountAmount = $discount->value;
        } else {
            $discountTypeOption = $discount->type_option;
            switch ($discount->type_option) {
                case 'amount':
                    switch ($discount->target) {
                        case 'amount-minimum-order':
                            if ($discount->min_order_price <= $rawTotal) {
                                $couponDiscountAmount += $discount->value;
                            } else {
                                return [
                                    'error'   => true,
                                    'message' => trans('plugins/ecommerce::discount.minimum_order_amount_error', [
                                        'minimum_amount' => format_price($discount->min_order_price),
                                        'add_more'       => format_price($rawTotal - $discount->min_order_price),
                                    ]),
                                ];
                            }

                            break;
                        case 'all-orders':
                            $couponDiscountAmount += $discount->value;
                            break;
                        case 'specific-product':
                        case 'product-variant':
                            foreach ($cartItems as $item) {
                                if (in_array($item->id, $discount->products()->pluck('product_id')->all())) {
                                    $discountValue = $item->price - $discount->value;
                                    $couponDiscountAmount += max($discountValue, 0);
                                }
                            }
                            break;
                        default:
                            if ($countCart >= $discount->product_quantity) {
                                $couponDiscountAmount += $discount->value;
                            }
                            break;
                    }
                    break;
                case 'percentage':
                    switch ($discount->target) {
                        case 'amount-minimum-order':
                            if ($discount->min_order_price <= $rawTotal) {
                                $couponDiscountAmount = $rawTotal * $discount->value / 100;
                            }
                            break;
                        case 'all-orders':
                            $couponDiscountAmount = $rawTotal * $discount->value / 100;
                            break;
                        case 'specific-product':
                        case 'product-variant':
                            foreach ($cartItems as $item) {
                                if (in_array($item->id, $discount->products()->pluck('product_id')->all())) {
                                    $couponDiscountAmount += $item->price * $discount->value / 100;
                                }
                            }
                            break;
                        default:
                            if ($countCart >= $discount->product_quantity) {
                                $couponDiscountAmount += $rawTotal * $discount->value / 100;
                            }
                            break;
                    }
                    break;
                case 'same-price':
                    foreach ($cartItems as $item) {
                        if (in_array($discount->target, ['specific-product', 'product-variant']) &&
                            in_array($item->id, $discount->products()->pluck('product_id')->all())
                        ) {
                            $discountValue = $item->price - $discount->value;
                            $couponDiscountAmount += max($discountValue, 0);
                        } elseif ($product = $this->productRepository->findById($item->id)) {
                            $productCollections = $product
                                ->productCollections()
                                ->pluck('ec_product_collections.id')
                                ->all();

                            $discountProductCollections = $discount
                                ->productCollections()
                                ->pluck('ec_product_collections.id')
                                ->all();

                            if (!empty(array_intersect($productCollections, $discountProductCollections))) {
                                $discountValue = $item->price - $discount->value;
                                $couponDiscountAmount += max($discountValue, 0);
                            }
                        }
                    }
            }
        }

        if ($couponDiscountAmount < 0) {
            $couponDiscountAmount = 0;
        }

        if ($prefix) {
            switch ($discountTypeOption) {
                case 'percentage' || 'same-price':
                    Arr::set($sessionData, $prefix . 'coupon_discount_amount', $couponDiscountAmount);
                    OrderHelper::setOrderSessionData($token, $sessionData);
                    break;
                default:
                    Arr::set($sessionData, $prefix . 'coupon_discount_amount', 0);
                    break;
            }
        } else {
            Arr::set($sessionData, 'coupon_discount_amount', $couponDiscountAmount);
            OrderHelper::setOrderSessionData($token, $sessionData);
        }

        session()->put('applied_coupon_code', $couponCode);

        return [
            'error' => false,
            'data'  => [
                'discount_amount'      => $couponDiscountAmount,
                'is_free_shipping'     => $isFreeShipping,
                'discount_type_option' => $discount->type_option,
                'discount'             => $discount,
            ],
        ];
    }

    /**
     * @param string $couponCode
     * @param array $sessionData
     * @return Eloquent|Builder|Model|object|null
     */
    public function getCouponData(string $couponCode, array $sessionData)
    {
        $couponCode = trim($couponCode);

        return $this->discountRepository
            ->getModel()
            ->where('code', $couponCode)
            ->where('type', 'coupon')
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($query) use ($sessionData) {
                /**
                 * @var Builder $query
                 */
                $query
                    ->where(function ($sub) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->whereIn('type_option', ['amount', 'percentage'])
                            ->where(function ($subSub) {
                                /**
                                 * @var Builder $subSub
                                 */
                                return $subSub
                                    ->whereNull('end_date')
                                    ->orWhere('end_date', '>=', Carbon::now());
                            });
                    })
                    ->orWhere(function ($sub) use ($sessionData) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->where('type_option', 'shipping')
                            ->where('value', '>=', Arr::get($sessionData, 'shipping_amount', 0))
                            ->where(function ($subSub) use ($sessionData) {
                                /**
                                 * @var Builder $subSub
                                 */
                                return $subSub
                                    ->whereNull('target')
                                    ->orWhere('target', 'all-orders');
                            });
                    })
                    ->orWhere(function ($sub) {
                        /**
                         * @var Builder $sub
                         */
                        return $sub
                            ->where('type_option', 'same-price')
                            ->whereIn('target', ['group-products', 'specific-product', 'product-variant']);
                    });
            })
            ->where(function ($query) {
                /**
                 * @var Builder $query
                 */
                return $query
                    ->whereNull('quantity')
                    ->orWhereRaw('quantity > total_used');
            })
            ->first();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function applyCouponWhenCreatingOrderFromAdmin(Request $request): array
    {
        $couponCode = trim($request->input('coupon_code'));

        $sessionData = [
            'shipping_amount' => $request->input('shipping_amount'),
            'state'           => $request->input('state'),
        ];

        $discount = $this->getCouponData($couponCode, $sessionData);

        if (empty($discount)) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.invalid_coupon'),
            ];
        }

        if ($discount->target == 'customer') {
            $discountCustomers = $discount->customers()->pluck('customer_id')->all();
            if (!in_array($request->input('customer_id'), $discountCustomers)) {
                return [
                    'error'   => true,
                    'message' => trans('plugins/ecommerce::discount.invalid_coupon'),
                ];
            }
        }

        if (!$discount->can_use_with_promotion && Arr::get($sessionData, 'promotion_discount_amount')) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.cannot_use_same_time_with_other_discount_program'),
            ];
        }

        $couponDiscountAmount = 0;
        $isFreeShipping = false;

        if ($discount->type_option == 'shipping') {
            $isFreeShipping = true;
        } elseif ($discount->type_option === 'amount' && $discount->discount_on === 'per-order') {
            $couponDiscountAmount = $discount->value;
        } else {
            switch ($discount->type_option) {
                case 'amount':
                    switch ($discount->target) {
                        case 'amount-minimum-order':
                            if ($discount->min_order_price <= $request->input('sub_total')) {
                                $couponDiscountAmount += $discount->value * count($request->input('product_ids', []));
                            }
                            break;
                        case 'all-orders':
                            $couponDiscountAmount += $discount->value * count($request->input('product_ids', []));
                            break;
                        default:
                            if (count($request->input('product_ids', [])) >= $discount->product_quantity) {
                                $couponDiscountAmount += $discount->value * count($request->input('product_ids', []));
                            }
                            break;
                    }
                    break;
                case 'percentage':
                    switch ($discount->target) {
                        case 'amount-minimum-order':
                            if ($discount->min_order_price <= $request->input('sub_total')) {
                                $couponDiscountAmount = $request->input('sub_total') * $discount->value / 100;
                            }
                            break;
                        case 'all-orders':
                            $couponDiscountAmount = $request->input('sub_total') * $discount->value / 100;
                            break;
                        default:
                            if (count($request->input('product_ids', [])) >= $discount->product_quantity) {
                                $couponDiscountAmount += $request->input('sub_total') * $discount->value / 100;
                            }
                            break;
                    }
                    break;
                case 'same-price':
                    foreach ($request->input('product_ids', []) as $productId) {
                        $product = $this->productRepository->findById($productId);

                        if (!$product) {
                            break;
                        }

                        if (in_array($discount->target, ['specific-product', 'product-variant']) &&
                            in_array($productId, $discount->products()->pluck('product_id')->all())
                        ) {
                            $discountValue = $product->original_price - $discount->value;

                            $couponDiscountAmount += max($discountValue, 0);
                        } else {
                            $productCollections = $product
                                ->productCollections()
                                ->pluck('ec_product_collections.id')
                                ->all();

                            $discountProductCollections = $discount
                                ->productCollections()
                                ->pluck('product_collection_id')
                                ->all();

                            if (!empty(array_intersect($productCollections, $discountProductCollections))) {
                                $discountValue = $product->original_price - $discount->value;

                                $couponDiscountAmount += max($discountValue, 0);
                            }
                        }
                    }

                    break;
            }
        }

        if ($couponDiscountAmount < 0) {
            $couponDiscountAmount = 0;
        }

        return [
            'error' => false,
            'data'  => [
                'discount_amount'  => $couponDiscountAmount,
                'is_free_shipping' => $isFreeShipping,
            ],
        ];
    }
}
