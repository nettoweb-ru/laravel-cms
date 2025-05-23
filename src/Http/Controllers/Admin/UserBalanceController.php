<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActionsKid;

use Netto\Models\UserBalance as WorkModel;
use Netto\Http\Requests\Admin\UserBalanceRequest as WorkRequest;

class UserBalanceController extends BaseController
{
    use AdminActionsKid;

    protected string $baseRoute = 'user-balance';
    protected string $baseRouteParent = 'user';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_user',
        'create' => 'main.create_balance',
        'edit' => 'main.edit_balance',
    ];

    protected string $itemParentRelation = 'user';
    protected string $itemRouteId = 'balance';
    protected string $itemRouteParentId = 'user';

    protected array $viewId = [
        'edit' => 'cms::user.balance',
    ];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        $model = $this->createModel($request->query($this->itemRouteParentId));
        return $this->redirect($model, $request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        $model = $this->getModel($id);
        return $this->redirect($model, $request);
    }
}
