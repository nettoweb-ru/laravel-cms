<?php

namespace Netto\Models\Abstract;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Netto\Notifications\{ResetPassword, VerifyEmail};
use Netto\Models\{Permission, Role, UserBalance};
use Netto\Traits\{HasPermissions, IsBaseModel};

/**
 * @property Collection $balance
 * @property Collection $roles
 */

abstract class User extends BaseUser implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, IsBaseModel, HasPermissions;

    public string $permissionsTable = 'cms__users__permissions';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        static::setTraitEvents();
    }

    /**
     * @return HasMany
     */
    public function balance(): HasMany
    {
        return $this->hasMany(UserBalance::class)->orderBy('created_at');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'cms__users__roles', 'object_id', 'related_id');
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        $return = 0;
        if (!$this->exists) {
            return $return;
        }

        foreach ($this->balance->all() as $item) {
            /** @var UserBalance $item */
            $return += $item->getAttribute('value');
        }

        return $return;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermissionThroughRole(Permission $permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermissionTo(Permission $permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission->getAttribute('slug'));
    }

    /**
     * @param ...$roles
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isAdministrator(): bool
    {
        $role = get_admin_role();
        return $this->hasRole($role);
    }

    /**
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }
}
