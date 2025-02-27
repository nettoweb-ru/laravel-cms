<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AdminLocale extends BaseLocale
{
    public const LANGUAGES = [
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

    protected const COOKIE_ID = 'netto-admin-lang';
    protected const DEFAULT_LANGUAGE = 'en';
    protected const LOCALES = [
        'en' => 'en_US',
        'fr' => 'fr_FR',
        'es' => 'es_ES',
        'de' => 'de_DE',
        'pt' => 'pt_PT',
        'ru' => 'ru_RU',
        /*'tr' => 'tr_TR',
        'ar' => 'ar_AE',
        'fa' => 'fa_IR',
        'zh' => 'zh_CN',
        'ja' => 'ja_JP',
        'hi' => 'hi_IN',
        'he' => 'he_IL',*/
    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $this->setLanguage();
        set_admin_cookie(self::COOKIE_ID, $language);

        return $this->setContentHeader($next($request), $language);
    }

    /**
     * @return string
     */
    protected function setLanguage(): string
    {
        $locales = self::LOCALES;
        foreach (config('cms.locales', []) as $key => $value) {
            $locales[$key] = $value;
        }

        $language = Cookie::get(self::COOKIE_ID, config('cms.default_language', self::DEFAULT_LANGUAGE));
        if (!array_key_exists($language, $locales)) {
            $language = self::DEFAULT_LANGUAGE;
        }

        set_language($language, $locales[$language]);
        return $language;
    }
}
