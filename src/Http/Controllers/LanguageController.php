<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Requests\LanguageRequest as WorkRequest;
use Netto\Models\Language as WorkModel;
use Netto\Traits\CrudControllerActions;

class LanguageController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected bool $autoSort = true;
    protected string $class = WorkModel::class;
    protected string $id = 'language';

    protected array $list = [
        'relations' => [],
        'title' => 'cms::main.list_language',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_language',
    ];

    protected array $route = [
        'index' => 'admin.language.index',
        'create' => 'admin.language.create',
        'delete' => 'admin.language.delete',
        'destroy' => 'admin.language.destroy',
        'edit' => 'admin.language.edit',
        'store' => 'admin.language.store',
        'update' => 'admin.language.update',
    ];

    protected string $title = 'cms::main.list_language';

    protected array $view = [
        'index' => 'cms::language.index',
        'edit' => 'cms::language.language'
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
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'boolean' => get_labels_boolean(),
        ];
    }
}
