<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\{HasAccessCheck, HasSystemAttribute};

/**
 * @property NavigationGroup $group
 */

class Navigation extends BaseModel
{
    use HasAccessCheck, HasSystemAttribute;

    public $table = 'cms__navigation';
    public $timestamps = false;

    public string $permissionsTable = 'cms__navigation__permissions';

    protected $attributes = [
        'sort' => 0,
        'is_active' => '1',
        'is_system' => '0',
    ];

    protected $casts = [
        'highlight' => 'array',
        'is_active' => 'boolean',
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
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(NavigationGroup::class, 'group_id');
    }
}
