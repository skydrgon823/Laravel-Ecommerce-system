<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderReturnReasonEnum;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderReturn extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_order_returns';

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'order_status',
        'return_status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'order_status'  => OrderStatusEnum::class,
        'return_status' => OrderReturnStatusEnum::class,
        'reason'        => OrderReturnReasonEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id')->withDefault();
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderReturnItem::class, 'order_return_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (OrderReturn $orderReturn) {
            OrderReturnItem::where('order_return_id', $orderReturn->id)->delete();
        });
    }
}
