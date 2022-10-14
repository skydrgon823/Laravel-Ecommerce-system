<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Illuminate\Support\Arr;
use OrderHelper;

class HandleRemoveCouponService
{
    /**
     * @var DiscountInterface
     */
    protected $discountRepository;

    /**
     * HandleRemoveCouponService constructor.
     * @param DiscountInterface $discountRepository
     */
    public function __construct(DiscountInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param string|null $prefix
     * @param bool $isForget
     * @return array
     */
    public function execute(?string $prefix = '', bool $isForget = true): array
    {
        if (!session()->has('applied_coupon_code')) {
            return [
                'error'   => true,
                'message' => trans('plugins/ecommerce::discount.not_used'),
            ];
        }

        $couponCode = session('applied_coupon_code');

        $discount = $this->discountRepository
            ->getModel()
            ->where('code', $couponCode)
            ->where('type', 'coupon')
            ->first();

        $token = OrderHelper::getOrderSessionToken();

        $sessionData = OrderHelper::getOrderSessionData($token);

        if ($discount && $discount->type_option === 'shipping') {
            Arr::set($sessionData, $prefix . 'is_free_shipping', false);
        }

        Arr::set($sessionData, $prefix . 'coupon_discount_amount', 0);
        OrderHelper::setOrderSessionData($token, $sessionData);

        if ($isForget) {
            session()->forget('applied_coupon_code');
        }

        return [
            'error' => false,
        ];
    }
}
