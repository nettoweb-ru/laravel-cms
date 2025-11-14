<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Netto\Http\Middleware\Abstract\Locale as BaseLocale;
use Symfony\Component\HttpFoundation\Response;

class LocalePublic extends BaseLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = get_default_language_code();
        $locales = array_map(function ($value) {
            return $value['locale'];
        }, get_language_list());

        set_language($language, $locales[$language]);
        return $this->setContentHeader($next($request), $language);
    }
}
