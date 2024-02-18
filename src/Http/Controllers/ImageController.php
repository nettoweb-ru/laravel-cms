<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\ImageRequest as WorkRequest;
use Netto\Models\Image as WorkModel;
use Netto\Models\Album as ParentModel;

use Netto\Traits\CrudControllerParentActions;

class ImageController extends Abstract\AdminCrudController
{
    use CrudControllerParentActions;

    protected bool $autoSort = true;
    protected string $class = WorkModel::class;
    protected string $id = 'image';

    protected array $list = [
        'columns' => [
            'sort' => [
                'title' => 'cms::main.attr_sort',
                'width' => 100
            ],
        ],
        'params' => [
            'page' => 1,
            'perPage' => 10,
            'sort' => 'sort',
            'sortDir' => 'asc',
        ],
        'relations' => [],
        'select' => [
            'id',
            'thumb',
        ],
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_image',
        'edit' => 'cms::main.edit_image',
    ];

    protected string $parentId = 'album';
    protected string $parentClass = ParentModel::class;
    protected string $parentAttr = 'album_id';

    protected array $route = [
        'index' => 'admin.album.edit',
        'create' => 'admin.album.image.create',
        'delete' => 'admin.album.image.delete',
        'destroy' => 'admin.album.image.destroy',
        'edit' => 'admin.album.image.edit',
        'store' => 'admin.album.image.store',
        'update' => 'admin.album.image.update',
    ];

    protected array $view = [
        'edit' => 'cms::album.image'
    ];

    /**
     * @param WorkRequest $request
     * @param string $parentId
     * @return RedirectResponse
     */
    public function store(WorkRequest $request, string $parentId): RedirectResponse
    {
        return $this->_store($request, $parentId);
    }

    /**
     * @param WorkRequest $request
     * @param string $parentId
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $parentId, string $id): RedirectResponse
    {
        return $this->_update($request, $parentId, $id);
    }

    /**
     * @param $parent
     * @return void
     */
    protected function addCrumbParent($parent): void
    {
        $this->title = $parent->name;
        $this->crumbs[] = [
            'title' => __('cms::main.list_album'),
            'link' => route('admin.album.index'),
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'thumb' => $object->getPreview(),
        ];
    }
}
