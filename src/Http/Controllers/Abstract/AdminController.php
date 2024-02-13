<?php

namespace Netto\Http\Controllers\Abstract;

use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

use Netto\Services\CmsService;

use App\Http\Controllers\Controller;

abstract class AdminController extends Controller
{
    protected array $crumbs = [];

    /**
     * @param string $id
     * @return int
     */
    protected function getCookie(string $id): int
    {
        $return = 1;
        $tab = Cookie::get($id);

        if (is_null($tab)) {
            CmsService::setAdminCookie($id, $return);
        } else {
            $return = $tab;
        }

        return $return;
    }

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
