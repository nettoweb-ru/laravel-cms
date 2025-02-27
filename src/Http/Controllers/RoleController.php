<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\RoleRequest as WorkRequest;
use Netto\Models\Permission;
use Netto\Models\Role as WorkModel;
use Netto\Traits\CrudControllerActions;

class RoleController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'role';

    protected array $list = [
        'columns' => [
            'name' => [
                'title' => 'cms::main.attr_name',
                'width' => 60
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
        ],
        'title' => 'cms::main.list_role',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_role',
    ];

    protected array $route = [
        'index' => 'admin.role.index',
        'create' => 'admin.role.create',
        'delete' => 'admin.role.delete',
        'destroy' => 'admin.role.destroy',
        'edit' => 'admin.role.edit',
        'store' => 'admin.role.store',
        'update' => 'admin.role.update',
    ];

    protected string $title = 'cms::main.list_role';

    protected array $sync = [
        'permissions',
    ];

    protected array $view = [
        'index' => 'cms::access.index',
        'edit' => 'cms::access.role'
    ];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        return $this->_store($request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        return $this->_update($request, $id);
    }

    /**
     * @param WorkModel $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'name' => $object->name,
            'slug' => $object->slug,
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'permission' => get_labels(Permission::class),
        ];
    }
}
