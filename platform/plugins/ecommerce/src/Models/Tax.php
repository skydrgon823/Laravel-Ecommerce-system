<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;

class Tax extends BaseModel
{
    use EnumCastable;

    /**
     * @var string
     */
    protected $table = 'ec_taxes';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'percentage',
        'priority',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
