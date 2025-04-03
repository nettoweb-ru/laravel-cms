<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\MenuItemRequest as WorkRequest;
use Netto\Models\Menu as ParentModel;
use Netto\Models\MenuItem as WorkModel;
use Netto\Models\Role;
use Netto\Traits\CrudControllerParentActions;

class MenuItemController extends Abstract\AdminCrudController
{
    use CrudControllerParentActions;

    protected bool $autoSort = true;
    protected string $class = WorkModel::class;
    protected string $id = 'menuItem';

    protected array $list = [
        'relations' => [],
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
    protected function getReference($object): array
    {
        return [
            'boolean' => get_labels_boolean(),
            'role' => get_labels(Role::class),
        ];
    }
}
