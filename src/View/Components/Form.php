<?php

namespace Netto\View\Components;

use Illuminate\Support\Facades\Cookie;
use Illuminate\View\Component;
use Illuminate\View\View;
use Netto\Services\CmsService;
use Netto\Services\LanguageService;

class Form extends Component
{
    private const LANG_COOKIE_ID = 'lang_public';

    public array $languages = [];
    public ?string $language = null;
    public bool $multiLang;

    public function __construct(bool $lang = false)
    {
        $this->multiLang = $lang;
        if (!$this->multiLang) {
            return;
        }

        $this->language = Cookie::get(self::LANG_COOKIE_ID);
        $languages = LanguageService::getList();

        if (is_null($this->language) || !array_key_exists($this->language, $languages)) {
            $this->language = LanguageService::getDefaultCode();
            CmsService::setAdminCookie(self::LANG_COOKIE_ID, $this->language);
        }

        if (count($languages) < 2) {
            return;
        }

        foreach ($languages as $key => $value) {
            $this->languages[] = [
                'slug' => $key,
                'name' => $value['name'],
            ];
        }
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('cms::components.form');
    }
}
