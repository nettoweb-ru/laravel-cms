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
        $fileSystem = new Filesystem();

        $fileSystem->copyDirectory(__DIR__.'/../../stub/app', app_path());
        $fileSystem->copy(__DIR__.'/../../stub/config/cms.php', config_path('cms.php'));
        $fileSystem->copyDirectory(__DIR__.'/../../stub/resources/views/auth', resource_path('views/auth'));
        $fileSystem->copyDirectory(__DIR__.'/../../stub/resources/views/components/layout', resource_path('views/components/layout'));

        $fileSystem->ensureDirectoryExists(resource_path('css'));
        $fileSystem->copy(__DIR__.'/../../stub/resources/css/styles.css', resource_path('css/styles.css'));

        $fileSystem->ensureDirectoryExists(resource_path('js'));
        $fileSystem->copy(__DIR__.'/../../stub/resources/js/styles.js', resource_path('js/styles.js'));

        $fileSystem->copy(__DIR__.'/../../stub/vite.config.js', base_path('vite.config.js'));

        $fileSystem->ensureDirectoryExists(storage_path('app/public/files'));
        $fileSystem->ensureDirectoryExists(storage_path('app/public/images'));
        $fileSystem->ensureDirectoryExists(storage_path('app/public/auto'));
        $fileSystem->ensureDirectoryExists(storage_path('app/auto'));

        $this->components->info('Nettoweb CMS was successfully installed');
    }
}
