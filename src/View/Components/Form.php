<?php

namespace Netto\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Netto\Services\LanguageService;

class Form extends Component
{
    public array $languages = [];
    public bool $multiLang;

    public function __construct(bool $lang = false)
    {
        $this->multiLang = $lang;
        if (!$this->multiLang) {
            return;
        }

        $languages = LanguageService::getList();

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
