<?php

namespace Botble\Faq\Models;

use Botble\Base\Models\BaseModel;

class FaqCategoryTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faq_categories_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'faq_categories_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
