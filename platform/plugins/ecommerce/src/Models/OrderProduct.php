<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderProduct extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_order_product';

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'qty',
        'weight',
        'price',
        'tax_amount',
        'options',
        'restock_quantity',
        'product_type',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getAmountFormatAttribute(): string
    {
        return format_price($this->price);
    }

    /**
     * @return string
     */
    public function getTotalFormatAttribute(): string
    {
        return format_price($this->price * $this->qty);
    }

    /**
     * @return HasMany
     */
    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id');
    }
}
