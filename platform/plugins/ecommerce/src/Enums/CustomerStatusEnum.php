<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static CustomerStatusEnum ACTIVATED()
 * @method static CustomerStatusEnum LOCKED()
 */
class CustomerStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';
    public const LOCKED = 'locked';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::customer.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::ACTIVATED:
                return Html::tag('span', self::ACTIVATED()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::LOCKED:
                return Html::tag('span', self::LOCKED()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
