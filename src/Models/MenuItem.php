<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\HasAccessCheck;

/**
 * @property Menu $menu
 */

class MenuItem extends BaseModel
{
    use HasAccessCheck;

    public $timestamps = false;
    public $table = 'cms__menu_items';

    public string $permissionsTable = 'cms__menu_items__permissions';

    protected $casts = [
        'is_active' => 'boolean',
        'is_blank' => 'boolean',
        'highlight' => 'array',
    ];

    protected $attributes = [
        'sort' => 0,
        'is_active' => '0',
        'is_blank' => '0',
    ];

    /**
     * @return BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
