<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Redirect as WorkModel;
use Netto\Http\Requests\Admin\RedirectRequest as WorkRequest;

class RedirectController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'redirect';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_redirect',
        'create' => 'main.create_redirect',
        'edit' => 'main.edit_redirect',
    ];

    protected string $itemRouteId = 'redirect';

    protected array $viewId = [
        'list' => 'cms::redirect.index',
        'edit' => 'cms::redirect.redirect',
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
        $statuses = array_merge(config('cms.redirects.statuses.redirect'), config('cms.redirects.statuses.error'));
        
        return [
            'boolean' => get_labels_boolean(),
            'status' => array_combine($statuses, $statuses),
        ];
    }
}
