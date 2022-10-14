<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wishlist extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_wish_lists';

    /**
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withDefault();
    }
}
