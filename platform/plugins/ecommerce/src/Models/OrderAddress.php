<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Enums\OrderAddressTypeEnum;
use Botble\Ecommerce\Traits\LocationTrait;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RvMedia;

class OrderAddress extends BaseModel
{
    use LocationTrait;
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_order_addresses';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'address',
        'zip_code',
        'order_id',
        'type',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'type' => OrderAddressTypeEnum::class,
    ];

    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        try {
            return (new Avatar())->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
