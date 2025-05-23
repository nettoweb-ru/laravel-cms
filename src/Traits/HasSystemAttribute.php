<?php

namespace Netto\Traits;

use Illuminate\Support\Facades\Session;
use Netto\Models\Abstract\Model;

trait HasSystemAttribute
{
    /**
     * @return void
     */
    protected static function setSystemTraitEvents(): void
    {
        self::deleting(function(Model $model): bool {
            if ($model->getAttribute('is_system')) {
                Session::put('status', __('main.error_deleting_default_model'));
                return false;
            }

            return true;
        });
    }
}
