<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class ProductAttributeSetTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_attribute_sets_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ec_product_attribute_sets_id',
        'title',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
