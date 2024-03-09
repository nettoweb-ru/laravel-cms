<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Netto\Services\LanguageService;
use Symfony\Component\HttpFoundation\Response;

class UserLocale
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
        $locale = $locales[$language];

        app()->setLocale($language);
        setlocale(LC_ALL, $locale.'.utf8');

        config()->set('locale', $locale);
        config()->set('text_dir', get_text_direction($language));

        $response = $next($request);
        $response->header('Content-Language', $language);

        return $response;
    }
}
