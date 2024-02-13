<?php

namespace Netto\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permissions
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param $permission
     * @return Response
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        /** @var User $user */
        $user = auth()->user();

        if (!is_null($permission) && (is_null($user) || !$user->can($permission))) {
            abort(404);
        }

        return $next($request);
    }
}
