<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCustomer extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_discount_customers';

    /**
     * @var array
     */
    protected $fillable = [
        'discount_id',
        'customer_id',
    ];

    /**
     * @return BelongsTo
     */
    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withDefault();
    }
}
