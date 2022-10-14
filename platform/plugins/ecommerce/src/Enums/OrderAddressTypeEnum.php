<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static OrderAddressTypeEnum SHIPPING()
 * @method static OrderAddressTypeEnum BILLING()
 */
class OrderAddressTypeEnum extends Enum
{
    public const SHIPPING = 'shipping_address';
    public const BILLING = 'billing_address';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::order.order_address_types';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::SHIPPING:
                return Html::tag('span', self::SHIPPING()->label(), ['class' => 'text-success'])
                    ->toHtml();
            case self::BILLING:
                return Html::tag('span', self::BILLING()->label(), ['class' => 'text-info'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
