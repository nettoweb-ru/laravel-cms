<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Collection $roles
 */

class Permission extends Model
{
    public $timestamps = false;
    public $table = 'cms__permissions';

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'cms__permission__role',  'permission_id', 'role_id');
    }
}
