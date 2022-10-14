<?php

namespace Botble\Media\Models;

use BaseHelper;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Request;
use RvMedia;

class MediaFile extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'media_files';

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'mime_type',
        'type',
        'size',
        'url',
        'options',
        'folder_id',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (MediaFile $file) {
            if ($file->isForceDeleting()) {
                RvMedia::deleteFile($file);
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'id', 'folder_id');
    }

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        $type = 'document';

        foreach (RvMedia::getConfig('mime_types', []) as $key => $value) {
            if (in_array($this->attributes['mime_type'], $value)) {
                $type = $key;
                break;
            }
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getHumanSizeAttribute(): string
    {
        return BaseHelper::humanFilesize($this->attributes['size']);
    }

    /**
     * @return string
     */
    public function getIconAttribute(): string
    {
        switch ($this->type) {
            case 'image':
                $icon = 'far fa-file-image';
                break;
            case 'video':
                $icon = 'far fa-file-video';
                break;
            case 'pdf':
                $icon = 'far fa-file-pdf';
                break;
            case 'excel':
                $icon = 'far fa-file-excel';
                break;
            default:
                $icon = 'far fa-file-alt';
                break;
        }

        return $icon;
    }

    /**
     * @return bool
     */
    public function canGenerateThumbnails(): bool
    {
        return RvMedia::canGenerateThumbnails($this->mime_type);
    }

    /**
     * @return string|null
     */
    public function getPreviewUrlAttribute()
    {
        $preview = null;
        switch ($this->type) {
            case 'image':
            case 'pdf':
            case 'text':
            case 'video':
                $preview = RvMedia::url($this->url);
                break;
            case 'document':
                $config = config('core.media.media.preview.document', []);
                if (Arr::get($config, 'enabled') &&
                    Request::ip() !== '127.0.0.1' &&
                    in_array($this->mime_type, Arr::get($config, 'mime_types', [])) &&
                    $url = Arr::get($config, 'providers.' . Arr::get($config, 'default'))
                ) {
                    $preview = Str::replace('{url}', urlencode(RvMedia::url($this->url)), $url);
                }
                break;
        }

        return $preview;
    }

    /**
     * @return string|null
     */
    public function getPreviewTypeAttribute()
    {
        return Arr::get(config('core.media.media.preview', []), $this->type . '.type');
    }
}
