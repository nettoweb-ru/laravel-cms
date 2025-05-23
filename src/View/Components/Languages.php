<?php

namespace Netto\View\Components;

use Illuminate\View\{Component, View};
use Netto\Http\Middleware\LocaleAdmin;

class Languages extends Component
{
    public array $items = [];

    public function __construct()
    {
        $language = app()->getLocale();

        foreach (LocaleAdmin::LANGUAGES as $key => $value) {
            $this->items[$key] = [
                'title' => $value,
                'current' => ($key == $language)
            ];
        }
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('cms::components.languages');
    }
}
