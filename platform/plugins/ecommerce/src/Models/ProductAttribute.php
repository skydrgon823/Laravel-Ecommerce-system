<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use RvMedia;

class ProductAttribute extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_attributes';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'color',
        'status',
        'order',
        'attribute_set_id',
        'image',
        'is_default',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @param int $value
     * @return int
     */
    public function getAttributeSetIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * @param int $value
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productAttributeSet()
    {
        return $this->belongsTo(ProductAttributeSet::class, 'attribute_set_id');
    }

    /**
     * @param int $value
     * @return int
     */
    public function getGroupIdAttribute($value)
    {
        return (int)$value;
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductAttribute $productAttribute) {
            ProductVariationItem::where('attribute_id', $productAttribute->id)->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariationItems()
    {
        return $this->hasMany(ProductVariationItem::class, 'attribute_id');
    }

    /**
     * @param ProductAttributeSet $attributeSet
     * @param array $productVariations
     * @return string
     */
    public function getAttributeStyle($attributeSet = null, $productVariations = [])
    {
        if ($attributeSet && $attributeSet->use_image_from_product_variation) {
            foreach ($productVariations as $productVariation) {
                $attribute = $productVariation->productAttributes->where('attribute_set_id', $attributeSet->id)->first();
                if ($attribute && $attribute->id == $this->id && $productVariation->product->image) {
                    return 'background-image: url(' . RvMedia::getImageUrl($productVariation->product->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
                }
            }
        }

        if ($this->image) {
            return 'background-image: url(' . RvMedia::getImageUrl($this->image) . '); background-size: cover; background-repeat: no-repeat; background-position: center;';
        }

        return 'background-color: ' . ($this->color ?: '#000') . ';';
    }
}
