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
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español',
        'de' => 'Deutsch',
        'pt' => 'Portuguesa',
        'ru' => 'Русский',
        /*'tr' => 'Türkçe',
        'ar' => 'عربي',
        'fa' => 'فارسی',
        'zh' => '中文',
        'ja' => '日本語',
        'hi' => 'हिन्दी',
        'he' => 'עִבְרִית',*/
    ];

    public const LOCALES = [
        'en' => 'en_US',
        'fr' => 'fr_FR',
        'es' => 'es_ES',
        'de' => 'de_DE',
        'pt' => 'pt_PT',
        'ru' => 'ru_RU',
    ];

    private const COOKIE_ID = 'netto-admin-lang';

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = Cookie::get(self::COOKIE_ID);

        if (is_null($lang)) {
            $lang = self::DEFAULT;
            CmsService::setAdminCookie(self::COOKIE_ID, $lang);
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
            CmsService::setAdminCookie(self::COOKIE_ID, $language);
        }

        set_language($language, $locales[$language]);
        return $next($request);
    }
}
