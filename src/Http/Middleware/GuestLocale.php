<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Netto\Http\Middleware\AdminLocale as Locale;

class GuestLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        set_language(Locale::DEFAULT, Locale::LOCALES[Locale::DEFAULT]);
        return $next($request);
    }
}
