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
        $response = $next($request);

        if ($response->getStatusCode() == 200) {
            $canonical = RedirectService::getCanonicalUrl($request);
            $requested = RedirectService::getRequestedUrl($request);

            if ($requested == $canonical) {
                return $response;
            }

            return RedirectService::redirect(
                $requested,
                $canonical,
                $request->ip()
            );
        }

        return $response;
    }
}
