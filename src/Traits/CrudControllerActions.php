<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

trait CrudControllerActions
{
    /**
     * @return View
     */
    public function create(): View
    {
        $this->setRouteParams();
        $this->addCrumbIndex();

        return $this->_edit($this->getObject($this->class));
    }

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

        return $this->_delete([
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);
    }

    /**
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);

        $this->setRouteParams();
        return $this->_destroy($object);
    }

    /**
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);

        $this->setRouteParams();
        $this->addCrumbIndex();

        return $this->_edit($object);
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $this->setRouteParams();
        return $this->_list();
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

        return $this->_toggle([
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function setRouteParams(): void
    {
        foreach ($this->route as $key => $value) {
            $this->route[$key] = [
                'name' => $value,
                'parameters' => [],

            ];
        }
    }

    /**
     * @param FormRequest $formRequest
     * @return RedirectResponse
     */
    protected function _store(FormRequest $formRequest): RedirectResponse
    {
        $object = $this->getObject($this->class);
        $this->setRouteParams();

        return $this->_save($formRequest, $object);
    }

    /**
     * @param FormRequest $formRequest
     * @param string $id
     * @return RedirectResponse
     */
    protected function _update(FormRequest $formRequest, string $id): RedirectResponse
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);
        $this->setRouteParams();

        return $this->_save($formRequest, $object);
    }
}
