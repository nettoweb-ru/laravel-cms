<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection $images
 */

class Album extends Model
{
    public $timestamps = false;
    public $table = 'cms__albums';

    protected $attributes = [
        'sort' => 0,
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::deleting(function($model) {
            foreach ($model->images->all() as $image) {
                $image->delete();
            }
        });
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('sort')->with('translated');;
    }
}
