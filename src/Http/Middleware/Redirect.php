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
            $path = $request->path();
            $query = $request->getQueryString();

            if ($path == '/') {
                $path = '';
            } else {
                $path = "/{$path}";
            }

            if ($query) {
                if (empty($path)) {
                    $path .= '/';
                }

                $path .= "?{$query}";
            }

            $canonical = RedirectService::getHostCanonical($request).$path;

            $uri = $request->getRequestUri();
            if ($uri == '/') {
                $uri = '';
            }

            $requested = RedirectService::getHostRequested($request).$uri;

            if ($requested == $canonical) {
                return $response;
            }

            return redirect()->intended($canonical, 301);
        }

        return $response;
    }
}
