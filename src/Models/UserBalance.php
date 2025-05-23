<?php

namespace Netto\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Models\Abstract\Model as BaseModel;

/**
 * @property User $user
 */

class UserBalance extends BaseModel
{
    public $table = 'cms__user_balance';

    protected $attributes = [
        'value' => '0.00',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
