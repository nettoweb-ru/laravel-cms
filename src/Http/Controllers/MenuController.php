<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Netto\Http\Requests\MenuRequest as WorkRequest;
use Netto\Models\Language;
use Netto\Models\Menu as WorkModel;
use Netto\Services\CmsService;
use Netto\Services\LanguageService;
use Netto\Services\MenuService;
use Netto\Traits\CrudControllerActions;

class MenuController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'menu';

    protected array $list = [
        'columns' => [
            'name' => [
                'title' => 'cms::main.attr_name',
                'width' => 60
            ],
            'slug' => [
                'title' => 'cms::main.attr_slug',
                'width' => 20
            ],
            'lang_id' => [
                'title' => 'cms::main.attr_language',
                'width' => 20
            ],
        ],
        'params' => [
            'page' => 1,
            'perPage' => 10,
            'sort' => 'name',
            'sortDir' => 'asc',
        ],
        'relations' => [],
        'select' => [
            'id',
            'name',
            'slug',
            'lang_id',
        ],
        'title' => 'cms::main.list_menu',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_menu',
    ];

    protected array $route = [
        'index' => 'admin.menu.index',
        'create' => 'admin.menu.create',
        'delete' => 'admin.menu.delete',
        'destroy' => 'admin.menu.destroy',
        'edit' => 'admin.menu.edit',
        'store' => 'admin.menu.store',
        'update' => 'admin.menu.update',
    ];

    protected array $tabs = [
        'edit' => ['menu_tab'],
    ];

    protected string $title = 'cms::main.list_menu';

    protected array $view = [
        'index' => 'cms::menu.index',
        'edit' => 'cms::menu.menu'
    ];

    /**
     * @return View
     */
    public function create(): View
    {
        $this->setRouteParams();
        $this->addCrumbIndex();

        $object = $this->getObject($this->class);
        $object->setAttribute('lang_id', LanguageService::getDefaultId());

        return $this->_edit($object);
    }

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
            'slug' => $object->slug,
            'lang_id' => $object->language->name,
        ];
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'language' => CmsService::getModelLabels(Language::class),
            'menu_items' => MenuService::getDropdownOptions($object->id)
        ];
    }
}
