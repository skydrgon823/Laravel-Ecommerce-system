<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountProduct extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_discount_products';

    /**
     * @var array
     */
    protected $fillable = [
        'discount_id',
        'product_id',
    ];

    /**
     * @return BelongsTo
     */
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }
}
