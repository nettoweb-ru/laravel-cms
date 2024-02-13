<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Collection $permissions
 */

class Role extends Model
{
    public $timestamps = false;
    public $table = 'cms__roles';

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'cms__permission__role', 'role_id', 'permission_id');
    }
}
