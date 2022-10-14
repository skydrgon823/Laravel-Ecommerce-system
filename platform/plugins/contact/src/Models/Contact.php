<?php

namespace Botble\Contact\Models;

use Botble\Base\Supports\Avatar;
use Botble\Base\Traits\EnumCastable;
use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Base\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RvMedia;

class Contact extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subject',
        'content',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => ContactStatusEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ContactReply::class);
    }

    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        try {
            return (new Avatar())->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }
}
