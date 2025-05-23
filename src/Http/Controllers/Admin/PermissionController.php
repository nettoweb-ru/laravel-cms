<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Response};

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Permission as WorkModel;
use Netto\Http\Requests\Admin\PermissionRequest as WorkRequest;

class PermissionController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'permission';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.general_access',
        'create' => 'main.create_permission',
    ];

    protected string $itemRouteId = 'permission';

    protected array $viewId = [
        'list' => 'cms::access.index',
        'edit' => 'cms::access.permission',
    ];

    /**
     * @param string $id
     * @return Response
     */
    public function edit(string $id): Response
    {
        $model = $this->getModel($id);
        $actions = [
            'index' => $this->getRouteIndex(),
            'save' => $this->getRoute('update', $model),
        ];

        if (!$model->getAttribute('is_system')) {
            $actions['destroy'] = $this->getRoute('destroy', $model);
        }

        return $this->form($model, [
            'url' => $actions,
        ]);
    }

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
     * @param array $item
     * @return array
     */
    protected function getItem(array $item): array
    {
        $item['name'] = __($item['name']);
        return $item;
    }

    /**
     * @return string
     */
    protected function getRouteIndex(): string
    {
        return route($this->getRouteAdmin('role.index'));
    }
}
