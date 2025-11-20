<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Netto\Services\RedirectService;

class Authenticate extends Middleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param ...$guards
     * @return RedirectResponse|mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($redirect = RedirectService::processRequest($request)) {
            return $redirect;
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
}
