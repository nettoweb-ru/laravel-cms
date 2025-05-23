<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\HasSystemAttribute;

/**
 * @property Collection $roles
 */

class Permission extends BaseModel
{
    use HasSystemAttribute;

    public $timestamps = false;
    public $table = 'cms__permissions';

    protected $attributes = [
        'is_system' => '0',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        self::setSystemTraitEvents();
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'cms__roles__permissions', 'related_id', 'object_id');
    }
}
