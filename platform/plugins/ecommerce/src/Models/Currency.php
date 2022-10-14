<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class Currency extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_currencies';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'symbol',
        'is_prefix_symbol',
        'order',
        'decimals',
        'is_default',
        'exchange_rate',
    ];
}
