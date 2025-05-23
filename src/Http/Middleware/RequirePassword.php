<?php

namespace Netto\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Auth\Middleware\RequirePassword as BaseMiddleware;
use Illuminate\Http\{JsonResponse, RedirectResponse};

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

            /** @var User $user */
            $user = $request->user();
            $route = $user && $user->isAdministrator()
                ? 'admin.password.confirm'
                : 'password.confirm';

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?: $route)
            );
        }

        return $next($request);
    }
}
