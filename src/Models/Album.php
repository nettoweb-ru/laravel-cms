<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\{Collection, Relations\HasMany};
use Netto\Models\Abstract\Model as BaseModel;

/**
 * @property Collection $images
 */

class Album extends BaseModel
{
    public $timestamps = false;
    public $table = 'cms__photo_albums';

    protected $attributes = [
        'sort' => 0,
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::deleting(function(Album $model) {
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
        return $this->hasMany(Image::class)->orderBy('sort')->with('translated');
    }
}
