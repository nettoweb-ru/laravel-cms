<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Netto\Http\Requests\PublicationRequest as WorkRequest;
use Netto\Models\Album;
use Netto\Models\Language;
use Netto\Models\Publication as WorkModel;
use Netto\Services\LanguageService;
use Netto\Traits\CrudControllerActions;

class PublicationController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'publication';

    protected array $list = [
        'relations' => ['language'],
        'title' => 'cms::main.list_publication',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_publication',
    ];

    protected array $route = [
        'index' => 'admin.home',
        'create' => 'admin.publication.create',
        'delete' => 'admin.publication.delete',
        'destroy' => 'admin.publication.destroy',
        'edit' => 'admin.publication.edit',
        'store' => 'admin.publication.store',
        'update' => 'admin.publication.update',
    ];

    protected string $title = 'cms::main.list_publication';

    protected array $view = [
        'index' => 'cms::publication.index',
        'edit' => 'cms::publication.publication'
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
        $return = parent::getItem($object);
        if ($object->language) {
            $return['lang_id'] = $object->language->name;
        }

        return $return;
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'language' => get_labels(Language::class),
            'albums' => get_labels(Album::class),
        ];
    }
}
