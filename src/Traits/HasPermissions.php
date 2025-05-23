<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Netto\Models\Permission;

/**
 * @property Collection $permissions
 */

trait HasPermissions
{
    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, $this->permissionsTable, 'object_id', 'related_id');
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission)->count();
    }
}
