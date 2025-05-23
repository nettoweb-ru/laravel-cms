<?php

namespace Netto\Traits;

use Illuminate\Support\Facades\Gate;
use Netto\Models\Permission;

trait HasAccessCheck
{
    use HasPermissions;

    /**
     * Check if current user has access to object.
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        if ($this->permissions->count() == 0) {
            return true;
        }

        foreach ($this->permissions->all() as $permission) {
            /** @var Permission $permission */
            if (Gate::allows($permission->getAttribute('slug'))) {
                return true;
            }
        }

        return false;
    }
}
