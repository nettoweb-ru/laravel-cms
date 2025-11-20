<?php

declare(strict_types=1);

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Netto\Services\RedirectService;
use Symfony\Component\HttpFoundation\Response;

class Redirect
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($redirect = RedirectService::processRequest($request)) {
            return $redirect;
        }

        return $next($request);
    }
}
