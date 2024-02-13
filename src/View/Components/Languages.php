<?php

namespace Netto\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Netto\Http\Middleware\AdminLocale;

class Languages extends Component
{
    public array $items = [];

    public function __construct()
    {
        $language = app()->getLocale();

        foreach (AdminLocale::SUPPORTED as $key => $value) {
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
