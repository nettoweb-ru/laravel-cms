<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActionsKid;

use Netto\Models\MenuItem as WorkModel;
use Netto\Http\Requests\Admin\MenuItemRequest as WorkRequest;

use Netto\Models\Permission;

class MenuItemController extends BaseController
{
    use AdminActionsKid;

    protected string $baseRoute = 'menu-item';
    protected string $baseRouteParent = 'menu';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_menu',
        'create' => 'main.create_menu_item',
    ];

    protected string $itemParentRelation = 'menu';
    protected string $itemRouteId = 'item';
    protected string $itemRouteParentId = 'menu';

    protected array $syncRelations = ['permissions'];

    protected array $viewId = [
        'edit' => 'cms::menu.item',
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

    /**
     * @param $model
     * @return array
     */
    protected function getReference($model): array
    {
        return [
            'boolean' => get_labels_boolean(),
            'permission' => get_labels_translated(Permission::class),
        ];
    }
}
