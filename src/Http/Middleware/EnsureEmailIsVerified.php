<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * @param $route
     * @return string
     */
    public static function redirectTo($route): string
    {
        return static::class.':'.$route;
    }

    /**
     * @param $request
     * @param Closure $next
     * @param $redirectToRoute
     * @return RedirectResponse|mixed|never
     */
    public function handle($request, Closure $next, $redirectToRoute = null): mixed
    {
        $user = $request->user();

        if (!$user || ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())) {
            if ($request->expectsJson()) {
                abort(403, 'Your email address is not verified.');
            }

            $route = $user->hasRole(CMS_ADMIN_ROLE)
                ? 'admin.verification.notice'
                : 'verification.notice';

            return Redirect::guest(URL::route($redirectToRoute ?: $route));
        }

        return $next($request);
    }
}
