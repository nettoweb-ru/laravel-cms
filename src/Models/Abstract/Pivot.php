<?php

namespace Netto\Models\Abstract;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot as BaseModel;
use Netto\Traits\HasUploads;

/**
 * @property Model $parent
 */

abstract class Pivot extends BaseModel
{
    protected string $parentClass;

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        if (in_array(HasUploads::class, class_uses_recursive(static::class))) {
            self::saving(function(Pivot $model): void {
                /** @var HasUploads $model */
                $model->saveUploads();
            });

            self::saved(function(Pivot $model): void{
                /** @var HasUploads $model */
                $model->checkUploads();
            });

            self::deleted(function(Pivot $model): void{
                /** @var HasUploads $model */
                $model->deleteUploads();
            });
        }

        self::updated(function(Pivot $model): void {
            $model->parent->touch();
        });
    }

    /**
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne($this->parentClass, 'id', 'object_id');
    }
}
