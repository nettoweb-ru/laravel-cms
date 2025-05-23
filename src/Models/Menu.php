<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\{Collection, Relations\BelongsTo, Relations\HasMany};
use Netto\Models\Abstract\Model as BaseModel;

/**
 * @property Collection $items
 * @property Language $language
 */

class Menu extends BaseModel
{
    public $timestamps = false;
    public $table = 'cms__menus';

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
