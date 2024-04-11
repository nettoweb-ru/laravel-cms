<?php

namespace Netto\View\Components;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Illuminate\View\View;
use Netto\Models\Navigation as Model;

class Navigation extends Component
{
    public array $items = [];

    public function __construct()
    {
        static $return;

        if (is_null($return)) {
            $return = [];
            $items = [];

            foreach (Model::orderBy('group_id', 'asc')->orderBy('sort', 'asc')->with('permissions')->get() as $model) {
                $allowed = true;
                if ($model->permissions) {
                    foreach ($model->permissions->all() as $permission) {
                        if (!Gate::allows($permission->slug)) {
                            $allowed = false;
                            break;
                        }
                    }
                }

                if (!$allowed) {
                    continue;
                }

                $items[$model->group_id][$model->id] = [
                    'name' => $model->name,
                    'url' => $model->url,
                    'highlight' => $model->highlight,
                    'access' => $model->access,
                ];
            }

            foreach ($items as $groupId => $groupKids) {
                $currentGroup = false;
                $kids = [];

                foreach ($groupKids as $item) {
                    $currentItem = $item['highlight'] && request()->routeIs(...$item['highlight']);

                    if (!$currentGroup && $currentItem) {
                        $currentGroup = true;
                    }

                    $kids[] = [
                        'name' => __($item['name']),
                        'url' => route($item['url']),
                        'current' => $currentItem,
                    ];
                }

                $return[$groupId] = [
                    'name' => __('cms::main.navigation_'.$groupId),
                    'current' => $currentGroup,
                    'items' => $kids,
                ];
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
