<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class ProductCollectionTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_collections_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ec_product_collections_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
