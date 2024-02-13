<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Netto\Models\UserBalance;
use Netto\Traits\HasRolesAndPermissions;

/**
 * @property Collection $balance
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

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
     * @return float
     */
    public function getBalance(): float
    {
        if (!$this->exists) {
            return 0;
        }

        return DB::table((new UserBalance())->getTable())->sum('value');
    }

    /**
     * @return HasMany
     */
    public function balance(): HasMany
    {
        return $this->hasMany(UserBalance::class)->orderBy('created_at');
    }
}
