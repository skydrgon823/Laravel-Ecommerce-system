<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static DiscountTypeOptionEnum AMOUNT()
 * @method static DiscountTypeOptionEnum PERCENTAGE()
 * @method static DiscountTypeOptionEnum SHIPPING()
 * @method static DiscountTypeOptionEnum SAME_PRICE()
 */
class DiscountTypeOptionEnum extends Enum
{
    public const AMOUNT = 'amount';
    public const PERCENTAGE = 'percentage';
    public const SHIPPING = 'shipping';
    public const SAME_PRICE = 'same-price';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::discount.enums.type-options';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::AMOUNT:
                return Html::tag('span', self::AMOUNT()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::PERCENTAGE:
                return Html::tag('span', self::PERCENTAGE()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::SHIPPING:
                return Html::tag('span', self::SHIPPING()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::SAME_PRICE:
                return Html::tag('span', self::SAME_PRICE()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
