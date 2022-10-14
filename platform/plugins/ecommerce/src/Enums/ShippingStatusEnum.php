<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static ShippingStatusEnum NOT_APPROVED()
 * @method static ShippingStatusEnum APPROVED()
 * @method static ShippingStatusEnum ARRANGE_SHIPMENT()
 * @method static ShippingStatusEnum READY_TO_BE_SHIPPED_OUT()
 * @method static ShippingStatusEnum PICKING()
 * @method static ShippingStatusEnum PENDING()
 * @method static ShippingStatusEnum DELAY_PICKING()
 * @method static ShippingStatusEnum PICKED()
 * @method static ShippingStatusEnum NOT_PICKED()
 * @method static ShippingStatusEnum DELIVERING()
 * @method static ShippingStatusEnum DELIVERED()
 * @method static ShippingStatusEnum NOT_DELIVERED()
 * @method static ShippingStatusEnum AUDITED()
 * @method static ShippingStatusEnum CANCELED()
 */
class ShippingStatusEnum extends Enum
{
    public const NOT_APPROVED = 'not_approved';
    public const APPROVED = 'approved';
    public const PENDING = 'pending';
    public const ARRANGE_SHIPMENT = 'arrange_shipment';
    public const READY_TO_BE_SHIPPED_OUT = 'ready_to_be_shipped_out';
    public const PICKING = 'picking';
    public const DELAY_PICKING = 'delay_picking';
    public const PICKED = 'picked';
    public const NOT_PICKED = 'not_picked';
    public const DELIVERING = 'delivering';
    public const DELIVERED = 'delivered';
    public const NOT_DELIVERED = 'not_delivered';
    public const AUDITED = 'audited';
    public const CANCELED = 'canceled';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::shipping.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::NOT_APPROVED:
                return Html::tag('span', self::NOT_APPROVED()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();

            case self::APPROVED:
                return Html::tag('span', self::APPROVED()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();

            case self::PENDING:
                return Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();

            case self::PICKING:
                return Html::tag('span', self::PICKING()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();

            case self::DELAY_PICKING:
                return Html::tag('span', self::DELAY_PICKING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();

            case self::NOT_PICKED:
                return Html::tag('span', self::NOT_PICKED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();

            case self::ARRANGE_SHIPMENT:
                return Html::tag('span', self::ARRANGE_SHIPMENT()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();

            case self::READY_TO_BE_SHIPPED_OUT:
                return Html::tag('span', self::READY_TO_BE_SHIPPED_OUT()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();

            case self::DELIVERING:
                return Html::tag('span', self::DELIVERING()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();

            case self::DELIVERED:
                return Html::tag('span', self::DELIVERED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();

            case self::AUDITED:
                return Html::tag('span', self::AUDITED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();

            case self::NOT_DELIVERED:
                return Html::tag('span', self::NOT_DELIVERED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();

            case self::CANCELED:
                return Html::tag('span', self::CANCELED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();

            default:
                return parent::toHtml();
        }
    }
}
