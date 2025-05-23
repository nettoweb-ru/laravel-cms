<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Menu as WorkModel;
use Netto\Http\Requests\Admin\MenuRequest as WorkRequest;

use Netto\Services\MenuService;

class MenuController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'menu';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_menu',
        'create' => 'main.create_menu',
    ];

    protected string $itemRouteId = 'menu';

    protected array $viewId = [
        'list' => 'cms::menu.index',
        'edit' => 'cms::menu.menu',
    ];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        $model = $this->createModel();
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
        /** @var WorkModel $model */
        return [
            'language' => get_labels_language(),
            'menu_items' => MenuService::getDropdownOptions($model->getAttribute('id')),
        ];
    }
}
