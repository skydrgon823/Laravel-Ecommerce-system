<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class ProductTagTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_tags_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ec_product_tags_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
