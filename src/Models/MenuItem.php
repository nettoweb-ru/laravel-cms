<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Menu $menu
 * @property Collection $roles
 */

class MenuItem extends Model
{
    public $timestamps = false;
    public $table = 'cms__menu_items';

    protected $casts = [
        'is_active' => 'boolean',
        'is_blank' => 'boolean',
    ];

    protected $attributes = [
        'sort' => 0,
        'is_active' => false,
        'is_blank' => false,
    ];

    /**
     * @return BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'cms__menu_item__role', 'menu_item_id', 'role_id');
    }
}
