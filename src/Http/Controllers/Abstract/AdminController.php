<?php

namespace Netto\Http\Controllers\Abstract;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

abstract class AdminController extends Controller
{
    protected array $crumbs = [];

    /**
     * @param string $id
     * @param array $params
     * @return View
     */
    protected function getView(string $id, array $params = []): View
    {
        return view($id, array_merge([
            'title' => $this->getTitle(),
            'chain' => (count($this->crumbs) > 1) ? $this->crumbs : [],
        ], $params));
    }

    /**
     * @return string
     */
    private function getTitle(): string
    {
        $title = [__('cms::main.general_management')];
        foreach ($this->crumbs as $item) {
            $title[] = $item['title'];
        }

        return implode(config('cms.title_separator', ' | '), array_reverse($title));
    }
}
