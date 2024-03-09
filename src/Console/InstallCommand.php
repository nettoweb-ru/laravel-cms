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

        $path = [
            'controllers' => app_path('Http/Controllers'),
            'requests' => app_path('Http/Requests'),
            'models' => app_path('Models'),
            'middleware' => app_path('Http/Middleware'),
            'services' => app_path('Services'),
            'defaultTpl' => resource_path('views/components/layout'),
            'auth' => resource_path('views/auth'),
            'js' => resource_path('js/styles.js'),
            'storage' => [
                storage_path('app/public/files'),
                storage_path('app/public/images'),
                storage_path('app/public/auto'),
                storage_path('app/auto'),
            ],
        ];

        $fileSystem->ensureDirectoryExists($path['controllers']);
        $fileSystem->copyDirectory(__DIR__.'/../../stub/app/Http/Controllers', $path['controllers']);

        $fileSystem->ensureDirectoryExists($path['requests']);
        $fileSystem->copyDirectory(__DIR__.'/../../stub/app/Http/Requests', $path['requests']);

        $fileSystem->ensureDirectoryExists($path['middleware']);
        $fileSystem->copyDirectory(__DIR__.'/../../stub/app/Http/Middleware', $path['middleware']);

        $fileSystem->ensureDirectoryExists($path['models']);
        $fileSystem->copyDirectory(__DIR__.'/../../stub/app/Models', $path['models']);

        $fileSystem->ensureDirectoryExists($path['services']);
        $fileSystem->copyDirectory(__DIR__.'/../../stub/app/Services', $path['services']);

        $fileSystem->copy(__DIR__.'/../../stub/config/cms.php', config_path('cms.php'));

        $fileSystem->copyDirectory(__DIR__.'/../../stub/lang', lang_path());

        foreach ($path['storage'] as $item) {
            $fileSystem->ensureDirectoryExists($item);
        }

        $fileSystem->ensureDirectoryExists($path['defaultTpl']);

        $defaultTpl = $path['defaultTpl'].'/default.blade.php';
        if (!$fileSystem->exists($defaultTpl)) {
            $fileSystem->copy(__DIR__.'/../../stub/resources/views/components/layout/default.blade.php', $defaultTpl);
        }

        if (!$fileSystem->exists($path['auth'])) {
            $fileSystem->copyDirectory(__DIR__.'/../../stub/resources/views/auth', $path['auth']);
        }

        if (!$fileSystem->exists($path['js'])) {
            $fileSystem->copy(__DIR__.'/../../resources/js/styles.js', $path['js']);
        }

        $this->components->info('Nettoweb CMS was successfully installed');
    }
}
