<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class ProductFile extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'ec_product_files';

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'url',
        'extras',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'extras' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getFileNameAttribute()
    {
        return Arr::get($this->extras, 'name', '');
    }

    /**
     * @return int
     */
    public function getFileSizeAttribute()
    {
        return Arr::get($this->extras, 'size', 0);
    }

    /**
     * @return string
     */
    public function getMimeTypeAttribute()
    {
        return Arr::get($this->extras, 'mime_type', '');
    }

    /**
     * @return string
     */
    public function getFileExtensionAttribute()
    {
        return Arr::get($this->extras, 'extension', '');
    }

    /**
     * @return string
     */
    public function getBasenameAttribute()
    {
        return $this->file_name . '.' . $this->file_extension;
    }
}
