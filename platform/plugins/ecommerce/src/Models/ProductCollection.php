<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductCollection extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_collections';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'is_featured',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductCollection $collection) {
            $collection->discounts()->detach();
        });
    }

    /**
     * @return BelongsToMany
     */
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'ec_discount_customers', 'customer_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_collection_products',
                'product_collection_id',
                'product_id'
            )
            ->where('is_variation', 0);
    }

    /**
     * @return BelongsToMany
     */
    public function promotions()
    {
        return $this
            ->belongsToMany(Discount::class, 'ec_discount_product_collections', 'product_collection_id')
            ->where('type', 'promotion')
            ->where('start_date', '<=', Carbon::now())
            ->where('target', 'group-products')
            ->where(function ($query) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('product_quantity', 1);
    }
}
