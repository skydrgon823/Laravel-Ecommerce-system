<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class BrandTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_brands_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ec_brands_id',
        'name',
        'description'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
