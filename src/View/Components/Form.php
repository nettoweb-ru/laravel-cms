<?php

namespace Netto\View\Components;

use Illuminate\View\{Component, View};

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

        foreach (get_language_list() as $key => $value) {
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
