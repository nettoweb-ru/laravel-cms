<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Album as WorkModel;
use Netto\Http\Requests\Admin\AlbumRequest as WorkRequest;

class AlbumController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'album';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_album',
        'create' => 'main.create_album',
    ];

    protected string $itemRouteId = 'album';

    protected array $viewId = [
        'list' => 'cms::album.index',
        'edit' => 'cms::album.album',
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
}
