<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static StockStatusEnum IN_STOCK()
 * @method static StockStatusEnum OUT_OF_STOCK()
 * @method static StockStatusEnum ON_BACKORDER()
 */
class StockStatusEnum extends Enum
{
    public const IN_STOCK = 'in_stock';
    public const OUT_OF_STOCK = 'out_of_stock';
    public const ON_BACKORDER = 'on_backorder';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::products.stock_statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::IN_STOCK:
                return Html::tag('span', self::IN_STOCK()->label(), ['class' => 'text-success'])
                    ->toHtml();
            case self::OUT_OF_STOCK:
                return Html::tag('span', self::OUT_OF_STOCK()->label(), ['class' => 'text-danger'])
                    ->toHtml();
            case self::ON_BACKORDER:
                return Html::tag('span', self::ON_BACKORDER()->label(), ['class' => 'text-info'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
