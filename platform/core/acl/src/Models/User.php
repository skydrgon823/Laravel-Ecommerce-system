<?php

namespace Botble\ACL\Models;

use Botble\ACL\Notifications\ResetPasswordNotification;
use Botble\ACL\Traits\PermissionTrait;
use Botble\Base\Supports\Avatar;
use Botble\Media\Models\MediaFile;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use RvMedia;

/**
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use PermissionTrait;
    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'avatar_id',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        'permissions' => 'json',
    ];

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst((string)$value);
    }

    /**
     * Always capitalize the last name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst((string)$value);
    }

    /**
     * @return string
     * @deprecated since v5.15
     */
    public function getFullName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return ucfirst((string)$this->first_name) . ' ' . ucfirst((string)$this->last_name);
    }

    /**
     * @return BelongsTo
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar->url) {
            return RvMedia::url($this->avatar->url);
        }

        try {
            return (new Avatar())->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }

    /**
     * @param string $value
     * @return array
     */
    public function getPermissionsAttribute($value): array
    {
        try {
            return json_decode($value ?: '', true) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * Set mutator for the "permissions" attribute.
     *
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * @return bool
     */
    public function isSuperUser(): bool
    {
        return $this->super_user || $this->hasAccess(ACL_ROLE_SUPER_USER);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAccess($permission);
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperUser()) {
            return true;
        }

        return $this->hasAnyAccess($permissions);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return HasMany
     */
    public function activations(): HasMany
    {
        return $this->hasMany(Activation::class, 'user_id');
    }

    /**
     * {@inheritDoc}
     */
    public function inRole($role): bool
    {
        $roleId = null;
        if ($role instanceof Role) {
            $roleId = $role->getKey();
        }

        foreach ($this->roles as $instance) {
            /**
             * @var Role $instance
             */
            if ($role instanceof Role) {
                if ($instance->getKey() === $roleId) {
                    return true;
                }
            } elseif ($instance->getKey() == $role || $instance->slug == $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        if ($this->exists) {
            $this->activations()->delete();
            $this->roles()->detach();
        }

        return parent::delete();
    }
}
