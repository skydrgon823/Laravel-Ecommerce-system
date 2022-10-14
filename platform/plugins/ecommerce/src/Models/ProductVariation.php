<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_product_variations';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'configurable_product_id',
        'is_default',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function variationItems(): HasMany
    {
        return $this->hasMany(ProductVariationItem::class, 'variation_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function configurableProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'configurable_product_id')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'ec_product_variation_items',
            'variation_id',
            'attribute_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        self::deleted(function (ProductVariation $variation) {
            $variation->productAttributes()->detach();

            if ($variation->product) {
                $variation->product->delete();
                event(new DeletedContentEvent(PRODUCT_MODULE_SCREEN_NAME, request(), $variation->product));
            }
        });

        self::updated(function (ProductVariation $variation) {
            if ($variation->is_default) {
                app(UpdateDefaultProductService::class)->execute($variation->product);
            }
        });
    }
}
