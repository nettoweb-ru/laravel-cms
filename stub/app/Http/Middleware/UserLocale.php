<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Netto\Services\LanguageService;
use Netto\Http\Middleware\BaseLocale;
use Symfony\Component\HttpFoundation\Response;

class UserLocale extends BaseLocale
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = LanguageService::getDefaultCode();

        $locales = [];
        foreach (LanguageService::getList() as $key => $value) {
            $locales[$key] = $value['locale'];
        }

        set_language($language, $locales[$language]);
        return $this->setContentHeader($next($request), $language);
    }
}
