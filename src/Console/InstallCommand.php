<?php

namespace Netto\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Install Nettoweb CMS';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/app/Http/Controllers', app_path('Http/Controllers'));

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/app/Http/Requests', app_path('Http/Requests'));

        (new Filesystem)->ensureDirectoryExists(app_path('Models'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/app/Models', app_path('Models'));

        (new Filesystem)->copy(__DIR__.'/../../stub/config/cms.php', config_path('cms.php'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/lang', lang_path());
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/assets', public_path('assets'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/icons', public_path('icons'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/resources/css', resource_path('css/netto'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stub/resources/js', resource_path('js/netto'));

        if (!(new Filesystem())->exists(resource_path('views/components/layout/default.blade.php'))) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stub/resources/views/components/layout/default.blade.php', resource_path('views/components/layout'));
        }

        if (!(new Filesystem())->exists(resource_path('views/auth'))) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stub/resources/views/auth', resource_path('views/auth'));
        }

        (new Filesystem)->copy(__DIR__.'/../../resources/js/styles.js', resource_path('js'));

        (new Filesystem)->ensureDirectoryExists(storage_path('app/public/files'));
        (new Filesystem)->ensureDirectoryExists(storage_path('app/public/images'));
        (new Filesystem)->ensureDirectoryExists(storage_path('app/public/auto'));
        (new Filesystem)->ensureDirectoryExists(storage_path('app/auto'));

        $this->components->info('Nettoweb CMS was successfully installed');
    }
}
