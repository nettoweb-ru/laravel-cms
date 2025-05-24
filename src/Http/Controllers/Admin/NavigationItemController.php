<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActionsKid;

use Netto\Models\Navigation as WorkModel;
use Netto\Http\Requests\Admin\NavigationRequest as WorkRequest;

use Netto\Models\Permission;

class NavigationItemController extends BaseController
{
    use AdminActionsKid;

    protected string $baseRoute = 'navigation-item';
    protected string $baseRouteParent = 'navigation';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.general_navigation',
        'create' => 'main.create_menu_item',
    ];

    protected string $itemParentRelation = 'group';
    protected string $itemRouteId = 'item';
    protected string $itemRouteParentId = 'navigation';

    protected array $syncRelations = ['permissions'];

    protected array $viewId = [
        'edit' => 'cms::navigation.item',
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
     * @return string
     */
    protected function getHeader($model): string
    {
        /** @var WorkModel $model */
        return $model->exists
            ? __($model->getAttribute('name'))
            : __($this->crudTitle['create']);
    }

    /**
     * @param WorkModel $model
     * @return string
     */
    protected function getHeaderName(Model $model): string
    {
        $return = parent::getHeaderName($model);
        return __($return);
    }

    /**
     * @param array $item
     * @return array
     */
    protected function getItem(array $item): array
    {
        $item['name'] = __($item['name']);
        return $item;
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
