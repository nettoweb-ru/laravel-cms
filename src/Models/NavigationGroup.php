<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\{Collection, Relations\HasMany};
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\HasSystemAttribute;

/**
 * @property Collection $items
 */

class NavigationGroup extends BaseModel
{
    use HasSystemAttribute;

    public $table = 'cms__navigation_groups';
    public $timestamps = false;

    protected $attributes = [
        'sort' => 0,
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

        self::deleting(function(NavigationGroup $model): bool {
            foreach ($model->items->all() as $item) {
                /** @var Navigation $item */
                if (!$item->delete()) {
                    return false;
                }
            }

            return true;
        });

        self::setSystemTraitEvents();
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Navigation::class, 'group_id')->where('is_active', '1')->with('permissions')->orderBy('sort');
    }
}
