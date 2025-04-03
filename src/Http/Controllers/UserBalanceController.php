<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\UserBalanceRequest as WorkRequest;
use App\Models\User as ParentModel;
use Netto\Models\UserBalance as WorkModel;
use Netto\Traits\CrudControllerParentActions;

class UserBalanceController extends Abstract\AdminCrudController
{
    use CrudControllerParentActions;

    protected string $class = WorkModel::class;
    protected string $id = 'balance';

    protected array $list = [
        'relations' => [],
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
        $return = parent::getItem($object);

        if (isset($return['value'])) {
            $return['value'] = format_number($object->value, 2);
        }

        return $return;
    }
}
