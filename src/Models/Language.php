<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Model;
use Netto\Traits\HasDefaultAttribute;

class Language extends Model
{
    use HasDefaultAttribute;

    public $timestamps = false;
    public $table = 'cms__languages';

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $attributes = [
        'sort' => 0,
        'is_default' => false,
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::saved(function($model): void {
            $model->checkSavedDefault();
        });

        self::deleting(function($model): bool {
            return $model->checkDeletingDefault();
        });
    }
}
