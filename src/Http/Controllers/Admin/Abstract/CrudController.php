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

        $multiLingual = is_multilingual($model) ? $model->multiLingual : [];

        $tableObj = $model->getTable();
        $builder = DB::table($tableObj);

        $selectRaw = '';
        $selectMl = [];
        $sort = '';
        $aliases = [];
        $relations = [];
        $relationsObj = [];
        $qualified = [];
        $i = 1;

        foreach (array_unique(array_merge($columnKeys, array_keys($filter))) as $column) {
            $alias = "c{$i}";
            $aliases[$column] = $alias;
            $i++;

            if (str_contains($column, '.')) {
                [$relationCode, $relationColumn] = explode('.', $column);
                $relations[$relationCode][$relationColumn] = $alias;
                $relationsObj[$relationCode] = $model->{$relationCode}();
            } else if (in_array($column, $multiLingual)) {
                $selectMl[$column] = $alias;
            } else {
                $qualified[$alias] = "{$tableObj}.{$column}";
                $selectRaw .= "`{$tableObj}`.`{$column}` as `{$alias}`, ";
            }

            if ($column == $params['sort']) {
                $sort = $alias;
            }
        }

        $groupBy = [];

        foreach ($relations as $relationCode => $relationColumns) {
            /** @var Relation $relation */
            $relation = $relationsObj[$relationCode];
            $relationClass = get_class($relation);

            switch ($relationClass) {
                case BelongsTo::class:
                    /** @var BelongsTo $relation */
                    $related = $relation->getModel();
                    $tableRel = $related->getTable();
                    $builder->leftJoin($tableRel, $relation->getQualifiedForeignKeyName(), '=', $relation->getQualifiedOwnerKeyName());

                    $multiLingualRel = is_multilingual($related) ? $related->multiLingual : [];
                    $selectMlRel = [];

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        if (in_array($column, $multiLingualRel)) {
                            $selectMlRel[$column] = $alias;
                        } else {
                            $qualified[$alias] = "{$tableRel}.{$column}";
                            $selectRaw .= "`{$tableRel}`.`{$column}` as `{$alias}`, ";
                        }
                    }

                    if ($selectMlRel) {
                        /** @var IsMultiLingual $related */
                        $relationTranslated = $related->translated();

                        /** @var Pivot $pivot */
                        $pivot = new ($relationTranslated->getPivotClass())();
                        $tableMultilingualRel = $pivot->getTable();

                        $builder->join($tableMultilingualRel, function(JoinClause $join) use ($relationTranslated, $tableMultilingualRel) {
                            $join->on($relationTranslated->getQualifiedForeignPivotKeyName(), '=', $relationTranslated->getQualifiedParentKeyName());
                            $join->where("{$tableMultilingualRel}.lang_id", '=', get_default_language_id());
                        });

                        foreach ($selectMlRel as $column => $alias) {
                            $qualified[$alias] = "{$tableMultilingualRel}.{$column}";
                            $selectRaw .= "`{$tableMultilingualRel}`.`{$column}` as `{$alias}`, ";

                            if ($groupBy) {
                                $groupBy[] = $alias;
                            }
                        }
                    }
                    break;
                case HasOne::class:
                    /** @var HasOne $relation */
                    $related = $relation->getModel();
                    $tableRel = $related->getTable();
                    $builder->leftJoin($tableRel, $relation->getForeignKeyName(), '=', $relation->getQualifiedParentKeyName());

                    $multiLingualRel = is_multilingual($related) ? $related->multiLingual : [];
                    $selectMlRel = [];

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        if (in_array($column, $multiLingualRel)) {
                            $selectMlRel[$column] = $alias;
                        } else {
                            $qualified[$alias] = "{$tableRel}.{$column}";
                            $selectRaw .= "`{$tableRel}`.`{$column}` as `{$alias}`, ";
                        }
                    }

                    if ($selectMlRel) {
                        /** @var IsMultiLingual $related */
                        $relationTranslated = $related->translated();

                        /** @var Pivot $pivot */
                        $pivot = new ($relationTranslated->getPivotClass())();
                        $tableMultilingualRel = $pivot->getTable();

                        $builder->join($tableMultilingualRel, function(JoinClause $join) use ($relationTranslated, $tableMultilingualRel) {
                            $join->on($relationTranslated->getQualifiedForeignPivotKeyName(), '=', $relationTranslated->getQualifiedParentKeyName());
                            $join->where("{$tableMultilingualRel}.lang_id", '=', get_default_language_id());
                        });

                        foreach ($selectMlRel as $column => $alias) {
                            $qualified[$alias] = "{$tableMultilingualRel}.{$column}";
                            $selectRaw .= "`{$tableMultilingualRel}`.`{$column}` as `{$alias}`, ";

                            if ($groupBy) {
                                $groupBy[] = $alias;
                            }
                        }
                    }
                    break;
                case BelongsToMany::class:
                    /** @var BelongsToMany $relation */
                    $tableRel = $relation->getTable();
                    $builder->join($tableRel, function(JoinClause $join) use ($relation) {
                        $join->on($relation->getQualifiedForeignPivotKeyName(), '=', $relation->getQualifiedParentKeyName());
                    });

                    foreach (array_unique($relationColumns) as $column => $alias) {
                        $qualified[$alias] = $alias;
                        $selectRaw .= "concat('-', group_concat(distinct `{$tableRel}`.`{$column}` order by `{$tableRel}`.`{$column}` separator '-'), '-') as `{$alias}`, ";
                    }

                    $groupBy[] = $relation->getQualifiedParentKeyName();
                    break;
                default:
                    throw new NettoException("This widget doesn't support relation class {$relationClass}");
            }
        }

        if ($selectMl) {
            /** @var IsMultiLingual $model */
            $relation = $model->translated();

            /** @var Pivot $pivot */
            $pivot = new ($relation->getPivotClass())();
            $tableMultilingual = $pivot->getTable();

            $builder->join($tableMultilingual, function(JoinClause $join) use ($relation, $tableMultilingual) {
                $join->on($relation->getQualifiedForeignPivotKeyName(), '=', $relation->getQualifiedParentKeyName());
                $join->where("{$tableMultilingual}.lang_id", '=', get_default_language_id());
            });

            foreach ($selectMl as $column => $alias) {
                $qualified[$alias] = "{$tableMultilingual}.{$column}";
                $selectRaw .= "`{$tableMultilingual}`.`{$column}` as `{$alias}`, ";

                if ($groupBy) {
                    $groupBy[] = $alias;
                }
            }
        }

        foreach ($filter as $column => $value) {
            $alias = $aliases[$column];
            if (str_contains($column, '.')) {
                [$relationCode, $relationColumn] = explode('.', $column);
                if (get_class($relationsObj[$relationCode]) == BelongsToMany::class) {
                    $builder->having($alias, 'LIKE', "%-{$value}-%");
                } else {
                    $builder->where($qualified[$alias], '=', $value);
                }
            } else {
                $builder->where($qualified[$alias], '=', $value);
            }
        }

        if ($groupBy) {
            $builder->groupBy($groupBy);
        }

        $builder->selectRaw(rtrim($selectRaw, ', '));

        if ($sort) {
            $builder->orderBy($sort, $params['sortDir']);
        }

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
                    $this->itemRouteId => $obj->{$aliases['id']}
                ])
            ];

            foreach ($columnKeys as $column) {
                $alias = $aliases[$column];

                if ($column == 'is_active') {
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
