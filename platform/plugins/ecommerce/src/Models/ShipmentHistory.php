<?php

namespace Botble\Ecommerce\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentHistory extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_shipment_histories';

    /**
     * @var array
     */
    protected $fillable = [
        'action',
        'description',
        'user_id',
        'shipment_id',
        'order_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }
}
