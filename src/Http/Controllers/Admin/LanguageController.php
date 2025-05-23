<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Language as WorkModel;
use Netto\Http\Requests\Admin\LanguageRequest as WorkRequest;

class LanguageController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'language';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_language',
        'create' => 'main.create_language',
    ];

    protected string $itemRouteId = 'language';

    protected array $viewId = [
        'list' => 'cms::language.index',
        'edit' => 'cms::language.language',
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
        return [
            'boolean' => get_labels_boolean(),
        ];
    }
}
