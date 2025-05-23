<?php

namespace Netto\View\Components;

use Illuminate\View\{Component, View};
use Netto\Models\Navigation as WorkModel;
use Netto\Models\NavigationGroup;

class Navigation extends Component
{
    public array $items = [];

    public function __construct()
    {
        static $return;

        if (is_null($return)) {
            $return = [];

            foreach (NavigationGroup::query()->with('items')->orderBy('sort')->get() as $group) {
                /** @var NavigationGroup $group */

                $items = [];
                $currentGroup = false;

                foreach ($group->items as $item) {
                    /** @var WorkModel $item */
                    if (!$item->isAccessible()) {
                        continue;
                    }

                    if ($currentItem = request()->routeIs(...array_merge([$item->getAttribute('url')], $item->getAttribute('highlight')))) {
                        $currentGroup = true;
                    }

                    $items[] = [
                        'name' => __($item->getAttribute('name')),
                        'url' => route($item->getAttribute('url')),
                        'current' => $currentItem,
                    ];
                }

                if ($items) {
                    $return[$group->getAttribute('id')] = [
                        'name' => __($group->getAttribute('name')),
                        'current' => $currentGroup,
                        'items' => $items,
                    ];
                }
            }
        }

        $this->items = $return;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('cms::components.navigation');
    }
}
