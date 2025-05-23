<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Models\Abstract\Model as BaseModel;

/**
 * @property Album $album
 * @property Language $language
 */

class Publication extends BaseModel
{
    public $table = 'cms__publications';

    /**
     * @return BelongsTo
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
