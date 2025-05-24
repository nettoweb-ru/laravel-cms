<?php

namespace Netto\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Netto\Http\Middleware\Abstract\Locale as BaseLocale;
use Symfony\Component\HttpFoundation\Response;

class LocaleAdmin extends BaseLocale
{
    public const DEFAULT_LANGUAGE = 'ru';
    public const LANGUAGES = [
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español',
        'de' => 'Deutsch',
        'pt' => 'Portuguesa',
        'nl' => 'Nederlandse',
        'it' => 'Italiano',
        'ru' => 'Русский',
        'tr' => 'Türkçe',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국인',
        'hi' => 'हिन्दी',
        'ar' => 'عربي',
        'fa' => 'فارسی',
        'he' => 'עִבְרִית',
    ];

    private const COOKIE_ID = 'netto-admin-lang';

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
        $locales = get_admin_locales();

        $language = Cookie::get(self::COOKIE_ID, config('cms.default_language', self::DEFAULT_LANGUAGE));
        if (!array_key_exists($language, $locales)) {
            $language = self::DEFAULT_LANGUAGE;
        }

        set_language($language, $locales[$language]);
        return $language;
    }
}
