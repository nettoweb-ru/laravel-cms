<?php

namespace Netto\Http\Controllers\Public\Abstract;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\{Request, Response};

use App\Http\Controllers\Public\Abstract\Controller as BaseController;
use Netto\Models\Abstract\Model;

abstract class CrudController extends BaseController
{
    protected string $className;
    protected string $itemKey = 'id';
    protected array $listParams;
    protected ?int $listPerPage = 10;
    protected string $listTitle;
    protected string $publication;
    protected array $viewId;

    /**
     * @param string $id
     * @return Response
     */
    public function item(string $id): Response
    {
        return $this->view($this->viewId['item'], $this->getItemData($id));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        return $this->view($this->viewId['list'], $this->getListData($request));
    }

    /**
     * @param Builder $builder
     * @param array $params
     * @return void
     */
    protected function applyListParams(Builder &$builder, array $params): void
    {
        $model = $builder->getModel();
        $table = $model->getTable();

        $multiLingual = is_multilingual($model) ? $model->multiLingual : [];

        $select = ["{$table}.id AS id"];
        $with = $params['with'];
        $isMultiLingual = false;

        foreach ($params['select'] as $item) {
            if (in_array($item, $multiLingual)) {
                $isMultiLingual = true;
            } else if (str_contains($item, '.')) {
                $select[] = $item;
            } else {
                $select[] = "{$table}.{$item} AS {$item}";
            }
        }

        if ($isMultiLingual) {
            $with[] = 'translated';
        }

        $builder->select($select);
        $builder->with($with);

        foreach ($params['orderBy'] as $sort => $sortDir) {
            $builder->orderBy($sort, $sortDir);
        }
    }

    /**
     * @param Builder $builder
     * @param int|null $perPage
     * @param int $modelId
     * @return int
     */
    protected function getBackPage(Builder $builder, ?int $perPage, int $modelId): int
    {
        if (is_null($perPage)) {
            return 1;
        }

        $page = 1;
        $count = 0;
        $nav = [];

        foreach ($builder->pluck('id')->all() as $id) {
            $nav[$id] = $page;
            $count++;
            if ($count == $perPage) {
                $page++;
                $count = 0;
            }
        }

        return $nav[$modelId];
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getBackParams(int $id): array
    {
        $builder = $this->getBuilder();
        $this->applyListParams($builder, $this->listParams);

        $return = [];
        $page = $this->getBackPage($builder, $this->listPerPage, $id);

        if ($page > 1) {
            $return['page'] = $page;
        }

        return $return;
    }

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        /** @var Model $object */
        $object = new $this->className();
        $return = $object->newQuery();

        if ($object->hasAttribute('is_active')) {
            $return->where("{$object->getTable()}.is_active", '1');
        }

        return $return;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getItemBackLink(array $params = []): string
    {
        return '';
    }

    /**
     * @param string $id
     * @return array
     */
    protected function getItemData(string $id): array
    {
        $builder = $this->getBuilder();

        /** @var Model $model */
        $model = $builder->where("{$builder->getModel()->getTable()}.{$this->itemKey}", $id)->first();
        if (is_null($model)) {
            abort(404);
        }

        $this->addTitle($model->name);
        $this->addCrumb($model->name);

        $header = __($this->listTitle);
        $this->addTitle($header);
        $this->addCrumb($header, $this->getItemBackLink());

        $return = $this->getModelData($model);
        $this->prepareItem($model, $return);

        $return['content']['back'] = $this->getItemBackLink($this->getBackParams($model->getAttribute('id')));

        return $return;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getListData(Request $request): array
    {
        $return = $this->getPublicationData($this->publication);
        $page = $this->validatePage($request->query('page'));

        $builder = $this->getBuilder();
        $this->applyListParams($builder, $this->listParams);
        $collection = $builder->pluck('id');

        if ($this->listPerPage) {
            $id = $collection->slice(($page - 1) * $this->listPerPage, $this->listPerPage)->all();
            $max = (new LengthAwarePaginator($id, $collection->count(), $this->listPerPage, $page))->lastPage();

            $builder->whereIn("{$builder->getModel()->getTable()}.id", $id);
        } else {
            $max = 1;
        }

        if ($page > $max) {
            abort(404);
        }

        $items = [];
        foreach ($builder->get() as $item) {
            /** @var Model $item */
            $items[] = $this->prepareListItem($item);
        }

        $return['list'] = [
            'items' => $items,
            'navigation'=> [
                'max' => $max,
                'current' => $page,
                'link' => $return['head']['canonical'],
            ],
        ];

        if ($page > 1) {
            $return['head']['canonical'] .= "?page={$page}";
        }

        return $return;
    }

    /**
     * @param Model $model
     * @return array
     */
    protected function prepareListItem(Model $model): array
    {
        return $model->toArray();
    }

    /**
     * @param Model $model
     * @param array $return
     * @return void
     */
    protected function prepareItem(Model $model, array &$return): void
    {

    }

    /**
     * @param string|null $page
     * @return int
     */
    protected function validatePage(?string $page): int
    {
        if (is_null($page)) {
            return 1;
        }

        if (!is_numeric($page) || str_starts_with($page, '0')) {
            abort(404);
        }

        $return = (int) $page;
        if ($return <= 1) {
            abort(404);
        }

        return $return;
    }
}
