<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\AlbumRequest as WorkRequest;
use Netto\Models\Album as WorkModel;
use Netto\Traits\CrudControllerActions;

class AlbumController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected bool $autoSort = true;
    protected string $class = WorkModel::class;
    protected string $id = 'album';

    protected array $list = [
        'columns' => [
            'sort' => [
                'title' => 'cms::main.attr_sort',
                'width' => 5
            ],
            'name' => [
                'title' => 'cms::main.attr_name',
                'width' => 95
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
            'name',
            'sort',
        ],
        'title' => 'cms::main.list_album',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_album',
    ];

    protected array $route = [
        'index' => 'admin.album.index',
        'create' => 'admin.album.create',
        'delete' => 'admin.album.delete',
        'destroy' => 'admin.album.destroy',
        'edit' => 'admin.album.edit',
        'store' => 'admin.album.store',
        'update' => 'admin.album.update',
    ];

    protected array $tabs = [
        'edit' => ['album_tab'],
    ];

    protected string $title = 'cms::main.list_album';

    protected array $view = [
        'index' => 'cms::album.index',
        'edit' => 'cms::album.album'
    ];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        return $this->_store($request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        return $this->_update($request, $id);
    }

    /**
     * @param WorkModel $object
     * @return array
     */
    protected function getItem($object): array
    {
        return [
            'name' => $object->name,
            'sort' => $object->sort,
        ];
    }
}
