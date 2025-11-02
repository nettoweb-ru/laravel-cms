<?php

namespace Netto\Traits\Crud;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response};
use Netto\Exceptions\NettoException;

trait AdminActionsKid
{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $model = $this->createModel($request->query($this->itemRouteParentId));
        $this->setAutoSort($model);

        return $this->form($model, [
            'url' => [
                'index' => $this->getRouteIndex($model),
                'save' => $this->getRouteStore($model),
            ],
        ]);
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $model = $this->getModel($id);
        $model->delete();

        $status = session('status');
        if (is_null($status)) {
            $status = __('main.general_status_deleted');
            $route = $this->getRouteIndex($model);
        } else {
            Session::forget('status');
            $route = $this->getRoute('edit', $model);
        }

        return redirect()->to($route)->with('status', $status);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function edit(string $id): Response
    {
        $model = $this->getModel($id);

        return $this->form($model, [
            'url' => [
                'index' => $this->getRouteIndex($model),
                'save' => $this->getRoute('update', $model),
                'destroy' => $this->getRoute('destroy', $model),
            ],
        ]);
    }

    /**
     * @param Model|null $model
     * @return void
     */
    protected function addCrumbs(?Model $model = null): void
    {
        $parent = $model->{$this->itemParentRelation};
        $header = $this->getHeaderName($parent);

        $this->addTitle($header);
        $this->addCrumb($header, route($this->getRouteCrud('edit', $this->baseRouteParent), [$this->itemRouteParentId => $parent]));

        $header = __($this->crudTitle['list']);

        $this->addTitle($header);
        $this->addCrumb($header, route($this->getRouteCrud('index', $this->baseRouteParent)));
    }

    /**
     * @param mixed $parentId
     * @return Model
     */
    protected function createModel(mixed $parentId): Model
    {
        /** @var Model $return */
        $return = new $this->className();

        /** @var BelongsTo $relation */
        $relation = $return->{$this->itemParentRelation}();

        /** @var Model $parent */
        $parent = $relation->getRelated()->newQuery()->findOrFail($parentId);
        $return->setAttribute($relation->getForeignKeyName(), $parentId);
        $return->setRelation($this->itemParentRelation, $parent);

        return $return;
    }

    /**
     * @param Request $request
     * @return array
     * @throws NettoException
     */
    protected function getListArray(Request $request): array
    {
        if ($parentId = $request->get($this->itemRouteParentId)) {
            return $this->getList(
                $this->createModel($parentId),
                array_merge([
                    "{$this->itemParentRelation}.id" => [
                        'value' => $parentId,
                        'strict' => true,
                    ]
                ], $this->getListFilter($request))
            );
        }

        abort(400);
    }

    /**
     * @param string $id
     * @return Model
     */
    protected function getModel(string $id): Model
    {
        /** @var Model $model */
        $model = new $this->className();
        return $model->newQuery()->with($this->itemParentRelation)->findOrFail($id);
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getRouteIndex(Model $model): string
    {
        return route($this->getRouteCrud('edit', $this->baseRouteParent), [
            $this->itemRouteParentId => $model->{$this->itemParentRelation}
        ]);
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getRouteStore(Model $model): string
    {
        return route($this->getRouteCrud('store'), [
            $this->itemRouteParentId => $model->{$this->itemParentRelation}
        ]);
    }

    /**
     * @param $model
     * @param $request
     * @return RedirectResponse
     */
    protected function redirect($model, $request): RedirectResponse
    {
        if (!$this->save($model, $request)) {
            return back()->with('status', __('main.error_saving_model'));
        }

        $to = $request->get('button_apply')
            ? route($this->getRouteCrud('edit'), [$this->itemRouteId => $model])
            : $this->getRouteIndex($model);

        return redirect()->to($to)->with('status', __('main.general_status_saved'));
    }

    /**
     * @param Model $model
     * @return void
     */
    protected function setAutoSort(Model $model): void
    {
        if ($model->hasAttribute('sort')) {
            /** @var Model $parent */
            $parent = $model->{$this->itemParentRelation};
            $parentId = $parent->getAttribute('id');

            $builder = $model->newQuery()->whereHas($this->itemParentRelation, function(Builder $builder) use ($parentId) {
                $builder->where('id', $parentId);
            });

            $model->setAttribute('sort', $builder->max('sort') + static::DEFAULT_SORT_STEP);
        }
    }
}
