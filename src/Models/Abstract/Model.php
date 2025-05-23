<?php

namespace Netto\Models\Abstract;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Netto\Traits\IsBaseModel;

abstract class Model extends BaseModel
{
    use IsBaseModel;

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        static::setTraitEvents();
    }
}
