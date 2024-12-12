<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\MenuItemRequest as WorkRequest;
use Netto\Models\Menu as ParentModel;
use Netto\Models\MenuItem as WorkModel;

use Netto\Models\Role;
use Netto\Services\CmsService;
use Netto\Traits\CrudControllerParentActions;

class MenuItemController extends Abstract\AdminCrudController
{
    use CrudControllerParentActions;

    protected bool $autoSort = true;
    protected string $class = WorkModel::class;
    protected string $id = 'menuItem';

    protected array $list = [
        'columns' => [
            'sort' => [
                'title' => 'cms::main.attr_sort',
                'width' => 5
            ],
            'name' => [
                'title' => 'cms::main.attr_name',
                'width' => 35
            ],
            'link' => [
                'title' => 'cms::main.attr_link',
                'width' => 20
            ],
            'slug' => [
                'title' => 'cms::main.attr_slug',
                'width' => 40
            ],
        ],
        'relations' => [],
        'select' => [
            'id',
            'name',
            'slug',
            'link',
            'is_active',
            'sort',
            'menu_id',
        ],
        'url' => [
            'create',
            'delete',
            'toggle',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_menu_item',
    ];

    protected array $route = [
        'index' => 'admin.menu.edit',
        'create' => 'admin.menu.menuItem.create',
        'delete' => 'admin.menu.menuItem.delete',
        'destroy' => 'admin.menu.menuItem.destroy',
        'edit' => 'admin.menu.menuItem.edit',
        'store' => 'admin.menu.menuItem.store',
        'update' => 'admin.menu.menuItem.update',
        'toggle' => 'admin.menu.menuItem.toggle',
    ];

    protected string $parentId = 'menu';
    protected string $parentClass = ParentModel::class;
    protected string $parentAttr = 'menu_id';

    protected array $sync = [
        'roles',
    ];

    protected array $view = [
        'edit' => 'cms::menu.item'
    ];

    /**
     * @param WorkRequest $request
     * @param string $parentId
     * @return RedirectResponse
     */
    public function store(WorkRequest $request, string $parentId): RedirectResponse
    {
        return $this->_store($request, $parentId);
    }

    /**
     * @param WorkRequest $request
     * @param string $parentId
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $parentId, string $id): RedirectResponse
    {
        return $this->_update($request, $parentId, $id);
    }

    /**
     * @param $parent
     * @return void
     */
    protected function addCrumbParent($parent): void
    {
        $this->title = $parent->name;
        $this->crumbs[] = [
            'title' => __('cms::main.list_menu'),
            'link' => route('admin.menu.index'),
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'name' => $object->name,
            'slug' => $object->slug,
            'link' => $object->link,
            'is_active' => $object->is_active,
            'sort' => $object->sort,
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'boolean' => CmsService::getBooleanLabels(),
            'role' => CmsService::getModelLabels(Role::class),
        ];
    }
}
