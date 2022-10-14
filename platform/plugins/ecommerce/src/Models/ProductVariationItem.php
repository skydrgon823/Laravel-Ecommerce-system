<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationItem extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_product_variation_items';

    /**
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'variation_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeSet()
    {
        return $this->hasMany(ProductAttributeSet::class, 'attribute_set_id');
    }
}
