<?php

namespace Netto\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Roles
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @param $permission
     * @return Response
     */
    public function handle(Request $request, Closure $next, $role, $permission = null): Response
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->hasRole($role)) {
            abort(404);
        }

        if (!is_null($permission) && !$user->can($permission)) {
            abort(404);
        }

        return $next($request);
    }
}
