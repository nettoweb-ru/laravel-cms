<?php

namespace Netto\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\{HasPermissions, HasSystemAttribute};

/**
 * @property Collection $permissions
 * @property Collection $users
 */

class Role extends BaseModel
{
    use HasPermissions, HasSystemAttribute;

    public $timestamps = false;
    public $table = 'cms__roles';

    protected $attributes = [
        'is_system' => '0',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public string $permissionsTable = 'cms__roles__permissions';

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
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cms__users__roles', 'related_id', 'object_id');
    }
}
