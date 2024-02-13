<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->hasMany(Image::class);
    }

    /**
     * @return HasMany
     */
    public function frames(): HasMany
    {
        return $this->hasMany(AlbumFrame::class);
    }
}
