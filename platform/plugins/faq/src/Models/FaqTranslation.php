<?php

namespace Botble\Faq\Models;

use Botble\Base\Models\BaseModel;

class FaqTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faqs_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'faqs_id',
        'question',
        'answer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
