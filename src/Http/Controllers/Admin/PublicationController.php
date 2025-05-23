<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Publication as WorkModel;
use Netto\Http\Requests\Admin\PublicationRequest as WorkRequest;

use Netto\Models\Album;

class PublicationController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'publication';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_publication',
        'create' => 'main.create_publication',
    ];

    protected string $itemRouteId = 'publication';

    protected array $viewId = [
        'list' => 'cms::publication.index',
        'edit' => 'cms::publication.publication',
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
     * @return WorkModel
     */
    protected function createModel(): WorkModel
    {
        $return = new $this->className();
        $return->setAttribute('lang_id', get_default_language_id());

        return $return;
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
            'albums' => get_labels(Album::class),
        ];
    }
}
