<?php

namespace Netto\Console\Commands\Abstract;

use Illuminate\Console\Command as BaseCommand;
use Netto\Http\Middleware\LocaleAdmin;

abstract class Command extends BaseCommand
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $language = config('cms.default_language', LocaleAdmin::DEFAULT_LANGUAGE);
        set_language($language, get_admin_locales()[$language]);

        $this->action();
    }

    /**
     * @return void
     */
    abstract protected function action(): void;
}
