<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\Model;
use Netto\Events\{ModelDeleted, ModelSaved};

trait IsBaseModel
{
    /**
     * @return void
     */
    protected static function setTraitEvents(): void
    {
        $traits = class_uses_recursive(static::class);
        $hasUploads = in_array(HasUploads::class, $traits);

        if (in_array(HasDefaultAttribute::class, $traits)) {
            self::saved(function(Model $model): void {
                /** @var HasDefaultAttribute $model */
                $model->saveDefault();
            });

            self::deleting(function(Model $model): bool {
                /** @var HasDefaultAttribute $model */
                return $model->checkDefault();
            });
        }

        if ($hasUploads) {
            self::saving(function(Model $model): void {
                /** @var HasUploads $model */
                $model->prepareUploads();
            });

            self::saved(function(Model $model): void {
                /** @var HasUploads $model */
                $model->checkUploads();
            });

            self::deleted(function(Model $model): void {
                /** @var HasUploads $model */
                $model->deleteUploads();
            });
        }

        if (in_array(IsMultiLingual::class, $traits)) {
            self::saving(function(Model $model): void {
                /** @var IsMultiLingual $model */
                $model->prepareMultiLingual();
            });

            self::saved(function(Model $model): void {
                /** @var IsMultiLingual $model */
                $model->saveMultiLingual();
            });

            self::deleting(function(Model $model): void {
                /** @var IsMultiLingual $model */
                foreach ($model->translated->all() as $item) {
                    $item->pivot->delete();
                }
            });
        }

        if ($hasUploads) {
            self::saving(function(Model $model): void {
                /** @var HasUploads $model */
                $model->saveUploads();
            });
        }

        self::saved(function(Model $model): void {
            ModelSaved::dispatch($model);
        });

        self::deleted(function(Model $model): void {
            ModelDeleted::dispatch($model);
        });
    }
}
