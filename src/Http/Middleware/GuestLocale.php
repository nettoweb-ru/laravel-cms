<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestLocale extends AdminLocale
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
