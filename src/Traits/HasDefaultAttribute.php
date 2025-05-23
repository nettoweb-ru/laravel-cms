<?php

namespace Netto\Traits;

use Illuminate\Support\Facades\Session;

trait HasDefaultAttribute
{
    /**
     * @return bool
     */
    protected function checkDefault(): bool
    {
        if ($this->getAttribute('is_default')) {
            Session::put('status', __('main.error_deleting_default_model'));
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    protected function saveDefault(): void
    {
        if (!$this->getOriginal('is_default') && $this->getAttribute('is_default')) {
            self::query()->where('is_default', '1')->whereNot('id', $this->getAttribute('id'))->update([
                'is_default' => '0',
            ]);
        }
    }
}
