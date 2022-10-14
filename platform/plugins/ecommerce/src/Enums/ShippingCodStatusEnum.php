<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static ShippingCodStatusEnum PENDING()
 * @method static ShippingCodStatusEnum COMPLETED()
 */
class ShippingCodStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::shipping.cod_statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::PENDING:
                return Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::COMPLETED:
                return Html::tag('span', self::COMPLETED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
