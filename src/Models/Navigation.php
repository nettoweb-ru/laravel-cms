<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Collection $permissions
 */

class Navigation extends Model
{
    public $table = 'cms__navigation';
    public $timestamps = false;

    protected $attributes = [
        'group_id' => 1,
        'sort' => 0,
        'url' => null,
        'highlight' => null,
    ];

    protected $casts = [
        'group_id' => 'integer',
        'sort' => 'integer',
        'name' => 'string',
        'url' => 'string',
        'highlight' => 'array',
    ];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'cms__navigation__permission', 'navigation_id', 'permission_id');
    }
}
