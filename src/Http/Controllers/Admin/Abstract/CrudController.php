<?php

namespace Netto\Http\Controllers\Admin\Abstract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne, Pivot, Relation};
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\{DB, Session};
use Illuminate\Http\{JsonResponse, Request, Response};

use Netto\Events\ModelSaved;
use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;
use Netto\Traits\IsMultiLingual;
use Netto\Exceptions\NettoException;

abstract class CrudController extends BaseController
{
    protected const DEFAULT_SORT_STEP = 10;

    protected string $className;

    protected string $baseRoute;
    protected array $crudTitle;
    protected string $itemRouteId;
    protected array $syncRelations = [];

    protected array $viewId;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->get('id', []);
        if (empty($id)) {
            abort(400);
        }

        $status = null;

        /** @var Model $object */
        $object = new $this->className();
        foreach ($object->newQuery()->whereIn('id', $id)->get() as $model) {
            /** @var Model $model */
            if (!$model->delete()) {
                $status = session('status');
                break;
            }
        }

        if (is_null($status)) {
            $status = __('main.general_status_deleted');
        } else {
            Session::forget('status');
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $this->addCrumbs();
        return $this->view($this->viewId['list']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function toggle(Request $request): JsonResponse
    {
        $id = $request->get('id', []);
        if (empty($id)) {
            abort(400);
        }

        $true = [];
        $false = [];

        /** @var Model $object */
        $object = new $this->className();
        foreach ($object->newQuery()->whereIn('id', $id)->get() as $model) {
            /** @var Model $model */
            if ($model->getAttribute('is_active')) {
                $false[$model->getAttribute('id')] = $model;
            } else {
                $true[$model->getAttribute('id')] = $model;
            }
        }

        if (!$true && !$false) {
            abort(400);
        }

        if ($true) {
            $object->newQuery()->whereIn('id', array_keys($true))->update([
                'is_active' => '1',
            ]);

            foreach ($true as $item) {
                ModelSaved::dispatch($item);
            }
        }

        if ($false) {
            $object->newQuery()->whereIn('id', array_keys($false))->update([
                'is_active' => '0',
            ]);

            foreach ($false as $item) {
                ModelSaved::dispatch($item);
            }
        }

        return response()->json([
            'status' => __('main.general_status_saved')
        ]);
    }

    /**
     * @param Model|null $model
     * @return void
     */
    abstract protected function addCrumbs(?Model $model = null): void;

    /**
     * @param Model $model
     * @param array $data
     * @return Response
     */
    protected function form(Model $model, array $data = []): Response
    {
        $header = $this->getHeader($model);

        $this->addTitle($header);
        $this->addCrumb($header);

        $this->addCrumbs($model);

        $data = array_merge_recursive($data, [
            'header' => $header,
            'object' => $model,
            'reference' => $this->getReference($model),
            'method' => $model->exists ? 'PATCH' : 'POST',
        ]);

        return $this->view($this->viewId['edit'], $data);
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getHeader(Model $model): string
    {
        if ($model->exists) {
            $return = $this->getHeaderName($model);

            if (empty($return)) {
                $return = __($this->crudTitle['edit'], ['id' => $model->getAttribute('id')]);
            }
        } else {
            $return = __($this->crudTitle['create']);
        }

        return $return;
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getHeaderName(Model $model): string
    {
        $return = '';

        if (is_multilingual($model)) {
            /** @var IsMultiLingual $model */
            $return = $model->getTranslated('name')[get_default_language_code()] ?? '';
        }

        if (empty($return)) {
            $return = $model->getAttribute('name');
        }

        return (string) $return;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function getItem(array $item): array
    {
        return $item;
    }

    /**
     * @param Model $model
     * @param array $filter
     * @return array
     * @throws NettoException
     */
    protected function getList(Model $model, array $filter = []): array
    {
        $params = request()->query();

        $columnKeys = isset($params['columns']) ? array_merge(['id'], array_keys($params['columns'])) : ['id'];
        if ($params['toggle']) {
            $columnKeys[] = 'is_active';
        }
        $columnKeys = array_unique($columnKeys);

        $multiLingualColumns = is_multilingual($model)
            ? $model->multiLingual
            : [];

        $select = [];
        $selectMainMultilingual = [];
        $selectRaw = "";

        $affectedRelations = [];
        $affectedRelationObjects = [];

        $tableObj = $model->getTable();
        $builder = DB::table($tableObj);

        $groupBy = false;
        $groupByColumn = null;
        $sortColumn = null;

        $i = 1;
        $aliases = [];
        $qualified = [];

        foreach (array_unique(array_merge($columnKeys, array_keys($filter))) as $column) {
            $alias = "c{$i}";
            $aliases[$column] = $alias;
            $i++;

            if (str_contains($column, '.')) {
                [$relationCode, $relationColumn] = explode('.', $column);
                $affectedRelations[$relationCode][$relationColumn] = $alias;
                $affectedRelationObjects[$relationCode] = $model->{$relationCode}();
            } else if (in_array($column, $multiLingualColumns)) {
                $selectMainMultilingual[$column] = $alias;
            } else {
                $columnName = "{$tableObj}.{$column}";
                $qualified[$alias] = $columnName;

                if ($column == 'id') {
                    $select[$columnName] = $column;
                    $groupByColumn = $columnName;
                } else {
                    $select[$columnName] = $alias;
                }

                if ($column == $params['sort']) {
                    $sortColumn = $columnName;
                }
            }
        }

        foreach ($affectedRelations as $relationCode => $relationColumns) {
            /** @var Relation $relation */
            $relation = $affectedRelationObjects[$relationCode];
            $relationClass = get_class($relation);

            switch ($relationClass) {
                case BelongsTo::class:
                    /** @var BelongsTo $relation */
                    $relatedModel = $relation->getModel();
                    $relationTable = $relatedModel->getTable();

                    $builder->leftJoin($relationTable, $relation->getQualifiedForeignKeyName(), '=', $relation->getQualifiedOwnerKeyName());

                    $selectRelatedMultilingual = [];
                    $multiLingualColumns = is_multilingual($relatedModel)
                        ? $relatedModel->multiLingual
                        : [];

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        if (in_array($column, $multiLingualColumns)) {
                            $selectRelatedMultilingual[$column] = $alias;
                        } else {
                            $columnName = "{$relationTable}.{$column}";
                            $qualified[$alias] = $columnName;
                            $select[$columnName] = $alias;

                            if ("{$relationCode}.{$column}" == $params['sort']) {
                                $sortColumn = $columnName;
                            }
                        }
                    }

                    if ($selectRelatedMultilingual) {
                        /** @var IsMultiLingual $relatedModel */
                        $relationTranslated = $relatedModel->translated();

                        /** @var Pivot $pivot */
                        $pivot = new ($relationTranslated->getPivotClass())();
                        $relationTableMultilingual = $pivot->getTable();

                        $builder->join($relationTableMultilingual, function(JoinClause $join) use ($relationTranslated, $relationTableMultilingual) {
                            $join->on($relationTranslated->getQualifiedForeignPivotKeyName(), '=', $relationTranslated->getQualifiedParentKeyName());
                            $join->where("{$relationTableMultilingual}.lang_id", '=', get_default_language_id());
                        });

                        foreach ($selectRelatedMultilingual as $column => $alias) {
                            $columnName = "{$relationTableMultilingual}.{$column}";
                            $qualified[$alias] = $columnName;
                            $select[$columnName] = $alias;

                            if ("{$relationCode}.{$column}" == $params['sort']) {
                                $sortColumn = $columnName;
                            }
                        }
                    }
                    break;
                case HasOne::class:
                    /** @var HasOne $relation */
                    $relatedModel = $relation->getModel();
                    $relationTable = $relatedModel->getTable();

                    $builder->leftJoin($relationTable, $relation->getQualifiedForeignKeyName(), '=', $relation->getQualifiedParentKeyName());

                    $selectRelatedMultilingual = [];
                    $multiLingualColumns = is_multilingual($relatedModel)
                        ? $relatedModel->multiLingual
                        : [];

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        if (in_array($column, $multiLingualColumns)) {
                            $selectRelatedMultilingual[$column] = $alias;
                        } else {
                            $columnName = "{$relationTable}.{$column}";
                            $qualified[$alias] = $columnName;
                            $select[$columnName] = $alias;

                            if ("{$relationCode}.{$column}" == $params['sort']) {
                                $sortColumn = $columnName;
                            }
                        }
                    }

                    if ($selectRelatedMultilingual) {
                        /** @var IsMultiLingual $relatedModel */
                        $relationTranslated = $relatedModel->translated();

                        /** @var Pivot $pivot */
                        $pivot = new ($relationTranslated->getPivotClass())();
                        $relationTableMultilingual = $pivot->getTable();

                        $builder->join($relationTableMultilingual, function(JoinClause $join) use ($relationTranslated, $relationTableMultilingual) {
                            $join->on($relationTranslated->getQualifiedForeignPivotKeyName(), '=', $relationTranslated->getQualifiedParentKeyName());
                            $join->where("{$relationTableMultilingual}.lang_id", '=', get_default_language_id());
                        });

                        foreach ($selectRelatedMultilingual as $column => $alias) {
                            $columnName = "{$relationTableMultilingual}.{$column}";
                            $qualified[$alias] = $columnName;
                            $select[$columnName] = $alias;

                            if ("{$relationCode}.{$column}" == $params['sort']) {
                                $sortColumn = $columnName;
                            }
                        }
                    }

                    break;
                case BelongsToMany::class:
                    /** @var BelongsToMany $relation */
                    $relationTable = $relation->getTable();

                    $builder->join($relationTable, function(JoinClause $join) use ($relation) {
                        $join->on($relation->getQualifiedForeignPivotKeyName(), '=', $relation->getQualifiedParentKeyName());
                    });

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        $selectRaw .= "concat('-', group_concat(distinct `{$relationTable}`.`{$column}` order by `{$relationTable}`.`{$column}` separator '-'), '-') as `{$alias}`, ";
                    }

                    $groupBy = true;
                    break;
                default:
                    throw new NettoException("This widget doesn't support relation class {$relationClass}");
            }
        }

        if ($selectMainMultilingual) {
            /** @var IsMultiLingual $model */
            $relationTranslated = $model->translated();

            /** @var Pivot $pivot */
            $pivot = new ($relationTranslated->getPivotClass())();
            $multilingualTable = $pivot->getTable();

            $builder->join($multilingualTable, function(JoinClause $join) use ($relationTranslated, $multilingualTable) {
                $join->on($relationTranslated->getQualifiedForeignPivotKeyName(), '=', $relationTranslated->getQualifiedParentKeyName());
                $join->where("{$multilingualTable}.lang_id", '=', get_default_language_id());
            });

            foreach ($selectMainMultilingual as $column => $alias) {
                $columnName = "{$multilingualTable}.{$column}";
                $qualified[$alias] = $columnName;
                $select[$columnName] = $alias;

                if ($column == $params['sort']) {
                    $sortColumn = $columnName;
                }
            }
        }

        foreach ($select as $column => $alias) {
            $selectRaw .= ($groupBy ? "MIN({$column})" : "{$column}");
            if ($alias) {
                $selectRaw .= " as `{$alias}`";
            }

            $selectRaw .= ", ";
        }

        foreach ($filter as $column => $value) {
            $alias = $aliases[$column];
            if (str_contains($column, '.')) {
                [$relationCode, $relationColumn] = explode('.', $column);
                if (get_class($affectedRelationObjects[$relationCode]) == BelongsToMany::class) {
                    $builder->having($alias, 'LIKE', "%-{$value}-%");
                } else {
                    $builder->where($qualified[$alias], '=', $value);
                }
            } else {
                $builder->where($qualified[$alias], '=', $value);
            }
        }

        if ($groupBy) {
            $builder->groupBy($groupByColumn);
        }

        if ($sortColumn) {
            $builder->orderBy($sortColumn, $params['sortDir']);
            if ($groupBy) {
                $builder->groupBy($sortColumn);
            }
        }

        $builder->selectRaw(rtrim($selectRaw, ', '));
        $collection = $builder->get();

        if (empty($params['perPage'])) {
            $list = $collection->all();

            $total = count($list);
            $maxPage = 1;
        } else {
            $list = $collection->slice(($params['page'] - 1) * $params['perPage'], $params['perPage'])->all();
            $pagination = new LengthAwarePaginator($list, count($collection), $params['perPage'], $params['page']);

            $total = $pagination->total();
            $maxPage = $pagination->lastPage();
        }

        $items = [];
        foreach ($list as $obj) {
            $item = [
                '_editUrl' => route($this->getRouteCrud('edit'), [
                    $this->itemRouteId => $obj->id
                ])
            ];

            foreach ($columnKeys as $column) {
                $alias = $aliases[$column];

                if ($column == 'id') {
                    $item[$column] = $obj->id;
                } elseif ($column == 'is_active') {
                    $item[$column] = (bool) $obj->{$alias};
                } elseif (str_starts_with($column, 'is_')) {
                    $item[$column] = $obj->{$alias} ? __('main.general_yes') : __('main.general_no');
                } elseif (str_ends_with($column, '_at') && $obj->{$alias}) {
                    $item[$column] = format_date($obj->{$alias});
                } elseif (str_ends_with($column, '_on') && $obj->{$alias}) {
                    $item[$column] = format_date($obj->{$alias}, \IntlDateFormatter::NONE);
                } else {
                    $item[$column] = $obj->{$alias};
                }
            }

            $items[] = $this->getItem($item);
        }

        return [
            'items' => $items,
            'total' => $total,
            'maxPage' => $maxPage,
        ];
    }

    /**
     * @param $model
     * @return array
     */
    protected function getReference($model): array
    {
        return [];
    }

    /**
     * @param string $chunk
     * @param Model $model
     * @return string
     */
    protected function getRoute(string $chunk, Model $model): string
    {
        return route($this->getRouteCrud($chunk), [
            $this->itemRouteId => $model
        ]);
    }

    /**
     * @param string $chunk
     * @param string|null $baseRoute
     * @return string
     */
    protected function getRouteCrud(string $chunk, ?string $baseRoute = null): string
    {
        return $this->getRouteAdmin(is_null($baseRoute) ? $this->baseRoute : $baseRoute).'.'.$chunk;
    }

    /**
     * @param Model $model
     * @param FormRequest $request
     * @return bool
     */
    protected function save(Model $model, FormRequest $request): bool
    {
        foreach ($request->validated() as $key => $value) {
            $model->setAttribute($key, $value);
        }

        if (!$model->save()) {
            return false;
        }

        foreach ($this->syncRelations as $relation) {
            $model->{$relation}()->sync(array_filter($request->get($relation, [])));
        }

        return true;
    }
}
