<?php

namespace Botble\ACL\Models;

use Illuminate\Support\Facades\Auth;
use Botble\Base\Models\BaseModel;

class UserMeta extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param string $key
     * @param null $value
     * @param int $userId
     * @return bool
     */
    public static function setMeta(string $key, $value = null, int $userId = 0): bool
    {
        if ($userId == 0) {
            $userId = Auth::id();
        }

        $meta = self::firstOrCreate([
            'user_id' => $userId,
            'key'     => $key,
        ]);

        return $meta->update(['value' => $value]);
    }

    /**
     * @param string $key
     * @param null $default
     * @param int $userId
     * @return string
     */
    public static function getMeta(string $key, $default = null, int $userId = 0): ?string
    {
        if ($userId == 0) {
            $userId = Auth::id();
        }

        $meta = self::where([
            'user_id' => $userId,
            'key'     => $key,
        ])->select('value')->first();

        if (!empty($meta)) {
            return $meta->value;
        }

        return $default;
    }
}
