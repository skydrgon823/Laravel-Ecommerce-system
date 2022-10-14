<?php

namespace Botble\Faq\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategory extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faq_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'order',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id');
    }
}
