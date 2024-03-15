<?php

namespace Netto;

use App\Http\Middleware\UserLocale;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Router;
use Netto\Http\Middleware\Authenticate;
use Netto\Http\Middleware\AdminLocale;
use Netto\Http\Middleware\EnsureEmailIsVerified;
use Netto\Http\Middleware\GuestLocale;
use Netto\Http\Middleware\RedirectIfAuthenticated;
use Netto\Http\Middleware\RequirePassword;
use Netto\Http\Middleware\Permissions;
use Netto\Http\Middleware\Roles;
use Netto\Models\Permission;
use Netto\Registrars\ResourceRegistrar;
use Netto\View\Components\Form;
use Netto\View\Components\Languages;
use Netto\View\Components\Navigation;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $registrar = new ResourceRegistrar($this->app['router']);
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function() use ($registrar) {
            return $registrar;
        });
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        define('CMS_LOCATION', config('cms::location', 'admin'));
        define('CMS_ADMIN_ROLE', 'administrator');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ImportCommand::class,
                Console\RefreshSitemapCommand::class,
                Console\RefreshSearchDatabase::class,
            ]);

            $this->publishes([
                __DIR__.'/../stub/lang' => lang_path(),
                __DIR__.'/../stub/public' => public_path(),
                __DIR__.'/../stub/resources/css/netto' => resource_path('css/netto'),
                __DIR__.'/../stub/resources/js/netto' => resource_path('js/netto'),
            ], 'laravel-assets');
        }

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('role', Roles::class);
        $router->aliasMiddleware('permission', Permissions::class);

        $router->aliasMiddleware('locale.admin', AdminLocale::class);
        $router->aliasMiddleware('locale.guest', GuestLocale::class);
        $router->aliasMiddleware('locale.public', UserLocale::class);

        $router->aliasMiddleware('guest.admin', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('auth.admin', Authenticate::class);

        $router->aliasMiddleware('verified', EnsureEmailIsVerified::class);
        $router->aliasMiddleware('password.confirm', RequirePassword::class);

        $router->middlewareGroup('admin', ['web', 'auth.admin', 'locale.admin', 'role:'.CMS_ADMIN_ROLE]);
        $router->middlewareGroup('admin.guest', ['web', 'guest.admin', 'locale.guest']);

        VerifyEmail::createUrlUsing(function(User $user): string {
            $route = $user->hasRole(CMS_ADMIN_ROLE)
                ? 'admin.verification.verify'
                : 'verification.verify';

            return URL::temporarySignedRoute(
                $route,
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
        });

        ResetPassword::createUrlUsing(function(User $user, string $token): string {
            $route = $user->hasRole(CMS_ADMIN_ROLE)
                ? 'admin.password.reset'
                : 'password.reset';

            return url(route($route, [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));
        });

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'cms');

        $this->loadViewComponentsAs('cms', [
            Languages::class,
            Navigation::class,
            Form::class,
        ]);

        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
        }

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
}
