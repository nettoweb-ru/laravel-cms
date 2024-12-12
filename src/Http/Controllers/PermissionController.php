<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\PermissionRequest as WorkRequest;
use Netto\Models\Permission as WorkModel;
use Netto\Traits\CrudControllerActions;

class PermissionController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'permission';

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
        'title' => 'cms::main.list_permission',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_permission',
    ];

    protected array $route = [
        'index' => 'admin.role.index',
        'create' => 'admin.permission.create',
        'delete' => 'admin.permission.delete',
        'destroy' => 'admin.permission.destroy',
        'edit' => 'admin.permission.edit',
        'store' => 'admin.permission.store',
        'update' => 'admin.permission.update',
    ];

    protected string $title = 'cms::main.list_permission';

    protected array $view = [
        'index' => 'cms::access.index',
        'edit' => 'cms::access.permission'
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
}
