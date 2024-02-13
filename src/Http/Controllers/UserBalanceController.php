<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\UserBalanceRequest as WorkRequest;
use App\Models\User as ParentModel;
use Netto\Models\UserBalance as WorkModel;

use Netto\Models\Role;
use Netto\Services\CmsService;
use Netto\Traits\CrudControllerParentActions;

class UserBalanceController extends Abstract\AdminCrudController
{
    use CrudControllerParentActions;

    protected string $class = WorkModel::class;
    protected string $id = 'balance';

    protected array $list = [
        'columns' => [
            'created_at' => [
                'title' => 'cms::main.attr_created_at',
                'width' => 60
            ],
            'value' => [
                'title' => 'cms::main.attr_value',
                'width' => 40
            ],
        ],
        'params' => [
            'page' => 1,
            'perPage' => 10,
            'sort' => 'created_at',
            'sortDir' => 'desc',
        ],
        'relations' => [],
        'select' => [
            'id',
            'created_at',
            'value',
        ],
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_user_balance',
        'edit' => 'cms::main.edit_user_balance',
    ];

    protected array $route = [
        'index' => 'admin.user.edit',
        'create' => 'admin.user.balance.create',
        'delete' => 'admin.user.balance.delete',
        'destroy' => 'admin.user.balance.destroy',
        'edit' => 'admin.user.balance.edit',
        'store' => 'admin.user.balance.store',
        'update' => 'admin.user.balance.update',
    ];

    protected string $parentId = 'user';
    protected string $parentClass = ParentModel::class;
    protected string $parentAttr = 'user_id';

    protected array $view = [
        'edit' => 'cms::user.balance'
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
            'title' => __('cms::main.list_user'),
            'link' => route('admin.user.index'),
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'created_at' => format_date($object->created_at),
            'value' => $object->value,
        ];
    }
}
