<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static OrderReturnReasonEnum NO_LONGER_WANT()
 * @method static OrderReturnReasonEnum DAMAGED()
 * @method static OrderReturnReasonEnum DEFECTIVE()
 * @method static OrderReturnReasonEnum INCORRECT_ITEM()
 * @method static OrderReturnReasonEnum ARRIVED_LATE()
 * @method static OrderReturnReasonEnum NOT_AS_DESCRIBED()
 */
class OrderReturnReasonEnum extends Enum
{
    public const NO_LONGER_WANT = 'no_longer_want';
    public const DAMAGED = 'damaged';
    public const DEFECTIVE = 'defective';
    public const INCORRECT_ITEM = 'incorrect_item';
    public const ARRIVED_LATE = 'arrived_late';
    public const NOT_AS_DESCRIBED = 'not_as_described';
    public const OTHER = 'other';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::order.order_return_reasons';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::NO_LONGER_WANT:
                return Html::tag('span', self::NO_LONGER_WANT()->label(), ['class' => 'text-danger'])
                    ->toHtml();
            case self::DEFECTIVE:
                return Html::tag('span', self::DEFECTIVE()->label(), ['class' => 'text-danger'])
                    ->toHtml();
            case self::INCORRECT_ITEM:
                return Html::tag('span', self::INCORRECT_ITEM()->label(), ['class' => 'text-warning'])
                    ->toHtml();
            case self::ARRIVED_LATE:
                return Html::tag('span', self::ARRIVED_LATE()->label(), ['class' => 'text-warning'])
                    ->toHtml();
            case self::NOT_AS_DESCRIBED:
                return Html::tag('span', self::NOT_AS_DESCRIBED()->label(), ['class' => 'text-warning'])
                    ->toHtml();
            case self::DAMAGED:
                return Html::tag('span', self::DAMAGED()->label(), ['class' => 'text-info'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
