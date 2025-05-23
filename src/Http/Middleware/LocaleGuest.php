<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Netto\Http\Middleware\LocaleAdmin as BaseLocale;
use Symfony\Component\HttpFoundation\Response;

class LocaleGuest extends BaseLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $this->setLanguage();
        return $this->setContentHeader($next($request), $language);
    }
}
