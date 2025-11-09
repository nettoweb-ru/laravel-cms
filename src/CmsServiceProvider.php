<?php

namespace Netto;

use App\Http\Middleware\LocalePublic;
use App\Models\User;
use Illuminate\Auth\Notifications\{ResetPassword, VerifyEmail};
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\{Carbon, ServiceProvider};
use Illuminate\Support\Facades\{Blade, Gate, Schedule, URL};
use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;
use Illuminate\Routing\Router;
use Netto\Http\Middleware\{Authenticate, EnsureEmailIsVerified, LocaleAdmin, LocaleGuest, RedirectIfAuthenticated, RequirePassword, Permissions, Roles};
use Netto\Models\Permission;
use Netto\Registrars\ResourceRegistrar;
use Netto\View\Components\{Captcha, Form, Languages, Navigation};
use Netto\Console\Commands\{RefreshSearchIndex, RefreshSitemap, ReportLogs};

class CmsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone', 'UTC'));

        if ($this->app->runningInConsole()) {
            $this->commands([
                RefreshSitemap::class,
                RefreshSearchIndex::class,
                ReportLogs::class,
            ]);
            $this->registerPublishedPaths();
        }

        $this->registerMiddleware();
        $this->registerMailTemplates();
        $this->registerScheduledTasks();

        $this->registerUserAbilities();
        $this->registerBladeDirectives();

        $this->setLoggingChannels();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');
        $this->loadViewComponentsAs('cms', [
            Languages::class,
            Navigation::class,
            Form::class,
            Captcha::class,
        ]);
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $registrar = new ResourceRegistrar($this->app['router']);
        $this->app->bind(OriginalRegistrar::class, function() use ($registrar) {
            return $registrar;
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/cms.php', 'cms');

    }

    /**
     * @return void
     */
    private function registerBladeDirectives(): void
    {
        Blade::directive('role', function ($role){
            return "<?php if (auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });
        Blade::directive('endrole', function (){
            return "<?php endif; ?>";
        });

        Blade::directive('permission', function ($permission) {
            return "<?php if (auth()->check() && auth()->user()->can({$permission})): ?>";
        });
        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
    }

    /**
     * @return void
     */
    private function registerMailTemplates(): void
    {
        VerifyEmail::createUrlUsing(function(User $user): string {
            $route = $user->isAdministrator()
                ? 'admin.verification.verify'
                : 'verification.verify';

            return URL::temporarySignedRoute(
                $route,
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
        });

        ResetPassword::createUrlUsing(function(User $user, string $token): string {
            $route = $user->isAdministrator()
                ? 'admin.password.reset'
                : 'password.reset';

            return url(route($route, [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));
        });
    }

    /**
     * @throws BindingResolutionException
     */
    private function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        foreach ([
            'admin.auth' => Authenticate::class,
            'admin.check' => RedirectIfAuthenticated::class,
            'locale.admin' => LocaleAdmin::class,
            'locale.guest' => LocaleGuest::class,
            'locale.public' => LocalePublic::class,
            'password.confirm' => RequirePassword::class,
            'permission' => Permissions::class,
            'role' => Roles::class,
            'verified' => EnsureEmailIsVerified::class,
         ] as $key => $value) {
            $router->aliasMiddleware($key, $value);
        }

        foreach ([
            'admin' => ['web', 'admin.auth', 'role:'.get_admin_role(), 'locale.admin'],
            'admin.guest' => ['web', 'admin.check', 'locale.guest'],
         ] as $key => $value) {
            $router->middlewareGroup($key, $value);
        }

        $router->pushMiddlewareToGroup('public', LocalePublic::class);
    }

    /**
     * @return void
     */
    private function registerPublishedPaths(): void
    {
        $this->publishes([
            __DIR__.'/../config/cms.php' => config_path('cms.php'),
            __DIR__.'/../stub/app' => app_path(),
            __DIR__.'/../stub/public/manifest.json' => public_path('manifest.json'),
            __DIR__.'/../stub/resources/views' => resource_path('views'),
            __DIR__.'/../stub/resources/css/styles.css' => resource_path('css/styles.css'),
            __DIR__.'/../stub/resources/css/error.css' => resource_path('css/error.css'),
            __DIR__.'/../stub/resources/js/styles.js' => resource_path('js/styles.js'),
            __DIR__.'/../stub/routes/web.php' => base_path('routes/web.php'),
            __DIR__.'/../stub/vite.config.js' => base_path('vite.config.js'),
            __DIR__.'/../stub/storage' => storage_path('app'),
            __DIR__.'/../stub/storage/public' => storage_path('app/public'),
            __DIR__.'/../stub/storage/public/files' => storage_path('app/public/files'),
            __DIR__.'/../stub/storage/public/images' => storage_path('app/public/images'),
            __DIR__.'/../stub/storage/public/auto' => storage_path('app/public/auto'),
        ], 'nettoweb-laravel-cms');

        $this->publishes([
            __DIR__.'/../stub/lang' => lang_path(),
            __DIR__.'/../stub/public/assets/css' => public_path('assets/css'),
            __DIR__.'/../stub/resources/css/netto' => resource_path('css/netto'),
            __DIR__.'/../stub/resources/js/netto' => resource_path('js/netto'),
        ], 'laravel-assets');
    }

    /**
     * @return void
     */
    private function registerScheduledTasks(): void
    {
        Schedule::command(ReportLogs::class)->hourlyAt(config('cms.schedule.hourly', 0));
        Schedule::command(RefreshSitemap::class)->dailyAt(config('cms.schedule.daily', 1));
        Schedule::command(RefreshSearchIndex::class)->weeklyOn(config('cms.schedule.weekly', 2));
    }

    /**
     * @return void
     */
    private function registerUserAbilities(): void
    {
        try {
            Permission::query()->get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    /**
     * @return void
     */
    private function setLoggingChannels(): void
    {
        config()->set('logging.channels.sent', [
            'driver' => 'single',
            'path' => storage_path('logs/sent.log'),
            'replace_placeholders' => true,
        ]);
    }
}
