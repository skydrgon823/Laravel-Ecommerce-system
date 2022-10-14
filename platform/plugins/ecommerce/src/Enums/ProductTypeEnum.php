<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;
use EcommerceHelper;
use Html;

/**
 * @method static ProductTypeEnum PHYSICAL()
 * @method static ProductTypeEnum DIGITAL()
 */
class ProductTypeEnum extends Enum
{
    public const PHYSICAL = 'physical';
    public const DIGITAL = 'digital';

    /**
     * @var string
     */
    public static $langPath = 'plugins/ecommerce::products.types';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::PHYSICAL:
                return Html::tag('span', self::PHYSICAL()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::DIGITAL:
                return Html::tag('span', self::DIGITAL()->label(), ['class' => 'label-primary status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }

    /**
     * @return string
     */
    public function toIcon(): string
    {
        if (!EcommerceHelper::isEnabledSupportDigitalProducts()) {
            return '';
        }

        switch ($this->value) {
            case self::PHYSICAL:
                return Html::tag('i', '', [
                    'class' => 'fa-solid fa-suitcase-rolling text-primary',
                    'title' => self::PHYSICAL()->label(),
                ])->toHtml();
            case self::DIGITAL:
                return Html::tag('i', '', [
                    'class' => 'fa-solid fa-microchip text-info',
                    'title' => self::DIGITAL()->label(),
                ])
                    ->toHtml();
            default:
                return Html::tag('i', '', ['class' => 'fa fa-camera'])
                    ->toHtml();
        }
    }
}
