<?php

namespace Netto\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as BaseMiddleware;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RequirePassword extends BaseMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param $redirectToRoute
     * @param $passwordTimeoutSeconds
     * @return JsonResponse|RedirectResponse|mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null, $passwordTimeoutSeconds = null): mixed
    {
        if ($this->shouldConfirmPassword($request, $passwordTimeoutSeconds)) {
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => 'Password confirmation required.',
                ], 423);
            }

            $route = $request->user()->hasRole(CMS_ADMIN_ROLE)
                ? 'admin.password.confirm'
                : 'password.confirm';

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?: $route)
            );
        }

        return $next($request);
    }
}
