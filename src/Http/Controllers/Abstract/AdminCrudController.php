<?php
namespace Netto\Http\Controllers\Abstract;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Model as WorkModel;
use Illuminate\Foundation\Http\FormRequest as WorkRequest;
use Netto\Models\Album;
use Netto\Services\CmsService;

abstract class AdminCrudController extends AdminController
{
    private const DEFAULT_LIST_PARAMS = [
        'page' => 1,
        'perPage' => 10,
        'sort' => 'id',
        'sortDir' => 'asc',
    ];

    protected bool $autoSort = false;
    protected string $class;
    protected string $id;
    protected array $list;
    protected array $messages;
    protected string $parentId;
    protected string $parentClass;
    protected string $parentAttr;
    protected array $route;
    protected array $tabs = [];
    protected string $title = '';
    protected array $sheets = [];
    protected array $sync = [];
    protected array $view;

    /**
     * @return View
     */
    public function index(): View
    {
        $this->crumbs[] = [
            'title' => __($this->title)
        ];

        return $this->getView($this->view['index']);
    }

    /**
     * @param array $filter
     * @return JsonResponse
     */
    protected function _delete(array $filter): JsonResponse
    {
        /** @var WorkModel $object */
        $object = new $this->class();
        $builder = $object::query();

        CmsService::setBuilderFilter($builder, $filter);

        foreach ($builder->get() as $model) {
            $model->delete();
        }

        $this->resetListPage();

        $status = session('status');
        if ($status) {
            Session::forget('status');
        } else {
            $status = __('cms::main.general_status_deleted');
        }

        return response()->json([
            'status' => $status
        ]);
    }

    /**
     * @param WorkModel $object
     * @return RedirectResponse
     */
    protected function _destroy(WorkModel $object): RedirectResponse
    {
        $object->delete();

        $status = session('status');
        if (is_null($status)) {
            $this->resetListPage();
            $status = __('cms::main.general_status_deleted');
            $route = route(...$this->route['index']);
        } else {
            $route = route($this->route['edit']['name'], array_merge($this->route['edit']['parameters'], [$this->id => $object->id]));
        }

        return Redirect::to($route)->with('status', $status);
    }

    /**
     * @param WorkModel $object
     * @param array $extraParams
     * @return View
     */
    protected function _edit(WorkModel $object, array $extraParams = []): View
    {
        $saveParams = $this->route['update']['parameters'];

        if ($object->exists) {
            $method = 'patch';
            $name = $object->getAttribute('name');
            $header = is_null($name)
                ? __($this->messages['edit'], ['id' => $object->id])
                : $name;
            $saveUrl = $this->route['update']['name'];
            $saveParams[$this->id] = $object->id;
            $destroyUrl = route($this->route['destroy']['name'], $saveParams);
        } else {
            $method = 'post';
            $header = __($this->messages['create']);
            $saveUrl = $this->route['store']['name'];
            $destroyUrl = null;

            if ($this->autoSort) {
                $object->setAttribute('sort', $this->getAutoSort($object));
            }
        }

        $url = [
            'save' => route($saveUrl, $saveParams),
            'index' => route(...$this->route['index']),
        ];

        if ($destroyUrl) {
            $url['destroy'] = $destroyUrl;
        }

        $this->crumbs[] = [
            'title' => $header,
        ];

        $params = [
            'object' => $object,
            'header' => $header,
            'method' => $method,
            'url' => $url,
            'reference' => $this->getReference($object),
        ];

        if (!empty($this->tabs['edit'])) {
            $params['tabs'] = [];
            foreach ($this->tabs['edit'] as $tabId) {
                $params['tabs'][$tabId] = $object->exists
                    ? $this->getCookie($tabId)
                    : 1;
            }
        }

        if (!empty($this->sheets['edit'])) {
            $params['sheets'] = [];
            foreach ($this->sheets['edit'] as $sheetId) {
                $params['sheets'][$sheetId] = $object->exists
                    ? $this->getCookie($sheetId)
                    : 1;
            }
        }

        return $this->getView($this->view['edit'], array_merge($params, $extraParams));
    }

    /**
     * @param array $filter
     * @return JsonResponse
     */
    protected function _list(array $filter = []): JsonResponse
    {
        $id = "{$this->id}_list";
        $params = Cookie::get($id);

        if (is_null($params)) {
            $params = $this->list['params'] ?: self::DEFAULT_LIST_PARAMS;
            CmsService::setAdminCookie($id, json_encode($params));
        } else {
            $params = json_decode($params, true);
        }

        /** @var Builder $class */
        $class = $this->class;

        $builder = $class::select($this->list['select'])->orderBy($params['sort'], $params['sortDir'])->with($this->list['relations']);
        CmsService::setBuilderFilter($builder, $filter);

        $collection = $builder->get();

        if ($params['perPage']) {
            $list = $collection->slice(($params['page'] - 1) * $params['perPage'], $params['perPage'])->all();
            $pagination = new LengthAwarePaginator($list, count($collection), $params['perPage'], $params['page']);

            $maxPage = $pagination->lastPage();
            $total = $pagination->total();
        } else {
            $list = $collection->all();

            $maxPage = 1;
            $total = count($list);
        }

        $columns = [];
        foreach ($this->list['columns'] as $key => $value) {
            $value['title'] = __($value['title']);
            $columns[$key] = $value;
        }

        $url = [];
        foreach ($this->list['url'] as $value) {
            $url[$value] = route(...$this->route[$value]);
        }

        $items = [];
        foreach ($list as $item) {
            $items[] = array_merge(['id' => $item->id, 'url' => route($this->route['edit']['name'], array_merge($this->route['edit']['parameters'], [$this->id => $item->id]))], $this->getItem($item));
        }

        return response()->json([
            'items' => $items,
            'id' => $id,
            'title' => empty($this->list['title']) ? '' : __($this->list['title']),
            'columns' => $columns,
            'params' => $params,
            'url' => $url,
            'nav' => [
                'max' => $maxPage,
                'total' => $total,
            ],
        ]);
    }

    /**
     * @param WorkRequest $request
     * @param WorkModel $model
     * @return RedirectResponse
     */
    protected function _save(WorkRequest $request, WorkModel $model): RedirectResponse
    {
        $attributes = $request->validated();

        if (method_exists($model, 'saveUploaded')) {
            if (!$model->saveUploaded($attributes)) {
                return back()->with('status', __('cms::main.error_saving_model'));
            }
        }

        if (method_exists($model, 'saveMultiLang')) {
            if (!$model->saveMultiLang($attributes)) {
                return back()->with('status', __('cms::main.error_saving_model'));
            }
        } else {
            foreach ($attributes as $key => $value) {
                $model->setAttribute($key, $value);
            }

            if (!$model->save()) {
                return back()->with('status', __('cms::main.error_saving_model'));
            }
        }

        foreach ($this->sync as $item) {
            $model->{$item}()->sync(array_filter($request->get($item, [])));
        }

        $model->refresh();

        return $this->redirect(empty($request->get('button_apply')), $model->id);
    }

    /**
     * @param array $filter
     * @return JsonResponse
     */
    protected function _toggle(array $filter): JsonResponse
    {
        /** @var WorkModel $object */
        $object = new $this->class();
        $builder = $object::query();

        CmsService::setBuilderFilter($builder, $filter);

        $true = [];
        $false = [];

        foreach ($builder->get() as $item) {
            if ($item->is_active) {
                $false[] = $item->id;
            } else {
                $true[] = $item->id;
            }
        }

        if ($true) {
            $builder = $object::query();
            CmsService::setBuilderFilter($builder, [
                'id' => [
                    'operator' => '=',
                    'value' => $true,
                ],
            ]);

            $builder->update([
                'is_active' => '1',
            ]);
        }

        if ($false) {
            $builder = $object::query();
            CmsService::setBuilderFilter($builder, [
                'id' => [
                    'operator' => '=',
                    'value' => $false,
                ],
            ]);

            $builder->update([
                'is_active' => '0',
            ]);
        }

        return response()->json([
            'status' => __('cms::main.general_status_saved')
        ]);
    }

    /**
     * @return void
     */
    protected function addCrumbIndex(): void
    {
        $this->crumbs[] = [
            'title' => __($this->title),
            'link' => route(...$this->route['index']),
        ];
    }

    /**
     * @param WorkModel $object
     * @return int
     */
    protected function getAutoSort(WorkModel $object): int
    {
        return CmsService::getModelSort($object);
    }

    /**
     * @param $object
     * @return array
     */
    abstract protected function getItem($object): array;

    /**
     * @param string $className
     * @param array|null $filter
     * @return WorkModel
     */
    protected function getObject(string $className, ?array $filter = null): WorkModel
    {
        /** @var WorkModel $return */
        $return = new $className();

        if (is_null($filter)) {
            return $return;
        }

        if (empty($filter)) {
            abort(404);
        }

        $builder = $return::query();
        CmsService::setBuilderFilter($builder, $filter);

        $return = $builder->get();
        if (!count($return)) {
            abort(404);
        }

        return $return->get(0);
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [];
    }

    /**
     * @param bool $save
     * @param int $id
     * @return RedirectResponse
     */
    protected function redirect(bool $save, int $id): RedirectResponse
    {
        $status = __('cms::main.general_status_saved');

        if ($save) {
            return Redirect::to(route(...$this->route['index']))->with('status', $status);
        }

        return Redirect::to(route($this->route['edit']['name'], array_merge($this->route['edit']['parameters'], [$this->id => $id])))->with('status', $status);
    }

    /**
     * @return void
     */
    private function resetListPage(): void
    {
        $id = "{$this->id}_list";
        $params = Cookie::get($id);

        if (is_null($params)) {
            $params = $this->list['params'] ?: self::DEFAULT_LIST_PARAMS;
        } else {
            $params = json_decode($params, true);
            $params['page'] = 1;
        }

        CmsService::setAdminCookie($id, json_encode($params));
    }
}
