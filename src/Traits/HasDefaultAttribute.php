<?php

namespace Netto\Traits;

use Illuminate\Support\Facades\Session;

trait HasDefaultAttribute
{
    /**
     * @return bool
     */
    private function checkDeletingDefault(): bool
    {
        if ($this->is_default) {
            Session::put('status', __('cms::main.error_deleting_default_model'));
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    private function checkSavedDefault(): void
    {
        if (!$this->original['is_default'] && $this->is_default) {
            self::where('is_default', '1')->whereNot('id', $this->id)->update([
                'is_default' => '0',
            ]);
        }
    }
}
