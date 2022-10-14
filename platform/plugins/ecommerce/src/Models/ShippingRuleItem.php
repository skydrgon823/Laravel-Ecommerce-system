<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class ShippingRuleItem extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_shipping_rule_items';

    /**
     * @var array
     */
    protected $fillable = [
        'shipping_rule_id',
        'city',
        'adjustment_price',
        'is_enabled',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param string $value
     */
    public function setAdjustmentPriceAttribute($value)
    {
        $this->attributes['adjustment_price'] = (float)str_replace(',', '', $value);
    }

    /**
     * @return string
     */
    public function getAdjustmentPriceAttribute()
    {
        return number_format($this->attributes['adjustment_price'], 0, false, false);
    }
}
