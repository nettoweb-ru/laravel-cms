<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActionsKid;

use Netto\Models\Image as WorkModel;
use Netto\Http\Requests\Admin\ImageRequest as WorkRequest;

class ImageController extends BaseController
{
    use AdminActionsKid;

    protected string $baseRoute = 'album-image';
    protected string $baseRouteParent = 'album';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_album',
        'create' => 'main.create_image',
        'edit' => 'main.edit_image',
    ];

    protected string $itemParentRelation = 'album';
    protected string $itemRouteId = 'image';
    protected string $itemRouteParentId = 'album';

    protected array $viewId = [
        'edit' => 'cms::album.image',
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
     * @param array $item
     * @return array
     */
    protected function getItem(array $item): array
    {
        $item['thumb'] = get_public_uploaded_path($item['thumb']);
        return $item;
    }
}
