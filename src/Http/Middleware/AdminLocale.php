<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Netto\Services\CmsService;
use Symfony\Component\HttpFoundation\Response;

class AdminLocale
{
    public const DEFAULT = 'ru';
    public const SUPPORTED = [
        'ru' => 'Русский',
        'en' => 'English',
    ];

    public const LOCALES = [
        'ru' => 'ru_RU',
        'en' => 'en_US',
    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = Cookie::get('lang');

        if (is_null($lang)) {
            $lang = self::DEFAULT;
            CmsService::setAdminCookie('lang', $lang);
        }

        $config = config('cms.locales', self::LOCALES);
        if (!is_array($config)) {
            $config = self::LOCALES;
        }

        $locales = self::LOCALES;
        foreach ($config as $key => $value) {
            if (array_key_exists($key, self::LOCALES)) {
                $locales[$key] = $value;
            }
        }

        if (empty($locales)) {
            $locales = self::LOCALES;
        }

        if (array_key_exists($lang, $locales)) {
            $language = $lang;
        } else {
            $language = self::DEFAULT;
            CmsService::setAdminCookie('lang', $language);
        }

        set_language($language, $locales[$language]);
        return $next($request);
    }
}
