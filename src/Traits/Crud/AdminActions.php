<?php

namespace Netto\Traits\Crud;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response};
use Illuminate\Database\Eloquent\Model as Model;

use Netto\Exceptions\NettoException;

trait AdminActions
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        $model = $this->createModel();
        $this->setAutoSort($model);

        return $this->form($model, [
            'url' => [
                'index' => $this->getRouteIndex(),
                'save' => $this->getRouteStore(),
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
            $route = $this->getRouteIndex();
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
                'index' => $this->getRouteIndex(),
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
        $header = __($this->crudTitle['list']);

        $this->addTitle($header);
        $this->addCrumb($header, $this->getRouteIndex());
    }

    /**
     * @return Model
     */
    protected function createModel(): Model
    {
        return new $this->className();
    }

    /**
     * @param Request $request
     * @return array
     * @throws NettoException
     */
    protected function getListArray(Request $request): array
    {
        return $this->getList(
            $this->createModel(),
            $this->getListFilter($request)
        );
    }

    /**
     * @param string $id
     * @return Model
     */
    protected function getModel(string $id): Model
    {
        return $this->createModel()->newQuery()->findOrFail($id);
    }

    /**
     * @return string
     */
    protected function getRouteIndex(): string
    {
        return route($this->getRouteCrud('index'));
    }

    /**
     * @return string
     */
    protected function getRouteStore(): string
    {
        return route($this->getRouteCrud('store'));
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
            : $this->getRouteIndex();

        return redirect()->to($to)->with('status', __('main.general_status_saved'));
    }

    /**
     * @param Model $model
     * @return void
     */
    protected function setAutoSort(Model $model): void
    {
        if ($model->hasAttribute('sort')) {
            $builder = $model->newQuery();

            $model->setAttribute('sort', $builder->max('sort') + static::DEFAULT_SORT_STEP);
        }
    }
}
