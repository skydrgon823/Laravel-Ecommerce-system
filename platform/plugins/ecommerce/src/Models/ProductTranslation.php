<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class ProductTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_products_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ec_products_id',
        'name',
        'description',
        'content',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
