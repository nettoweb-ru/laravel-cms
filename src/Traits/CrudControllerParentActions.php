<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

trait CrudControllerParentActions
{
    /**
     * @param string $parentId
     * @return View
     */
    public function create(string $parentId): View
    {
        $parent = $this->getObject($this->parentClass, [
            'id' => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);

        $object = $this->getObject($this->class);
        $object->setAttribute($this->parentAttr, $parent->id);

        $this->addCrumbParent($parent);

        $this->setRouteParams($parent->id);
        $this->addCrumbIndex();

        return $this->_edit($object);
    }

    /**
     * @param Request $request
     * @param string $parentId
     * @return JsonResponse
     */
    public function delete(Request $request, string $parentId): JsonResponse
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
            $this->parentAttr => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);
    }

    /**
     * @param string $parentId
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $parentId, string $id): RedirectResponse
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
            $this->parentAttr => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);

        $this->setRouteParams($parentId);
        return $this->_destroy($object);
    }

    /**
     * @param string $parentId
     * @param string $id
     * @return View
     */
    public function edit(string $parentId, string $id): View
    {
        $parent = $this->getObject($this->parentClass, [
            'id' => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);

        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id
            ],
        ]);

        $this->addCrumbParent($parent);

        $this->setRouteParams($parent->id);
        $this->addCrumbIndex();

        return $this->_edit($object);
    }

    /**
     * @param string $parentId
     * @return JsonResponse
     */
    public function list(string $parentId): JsonResponse
    {
        $this->setRouteParams($parentId);

        return $this->_list([
            $this->parentAttr => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param string $parentId
     * @return JsonResponse
     */
    public function toggle(Request $request, string $parentId): JsonResponse
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
            $this->parentAttr => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);
    }

    /**
     * @param FormRequest $formRequest
     * @param string $parentId
     * @return RedirectResponse
     */
    protected function _store(FormRequest $formRequest, string $parentId): RedirectResponse
    {
        $parent = $this->getObject($this->parentClass, [
            'id' => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);

        $object = $this->getObject($this->class);
        $object->setAttribute($this->parentAttr, $parent->id);

        $this->setRouteParams($parentId);

        return $this->_save($formRequest, $object);
    }

    /**
     * @param FormRequest $formRequest
     * @param string $parentId
     * @param string $id
     * @return RedirectResponse
     */
    protected function _update(FormRequest $formRequest, string $parentId, string $id): RedirectResponse
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
            $this->parentAttr => [
                'operator' => '=',
                'value' => $parentId,
            ],
        ]);

        $this->setRouteParams($parentId);

        return $this->_save($formRequest, $object);
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
     * @param Model $object
     * @return int
     */
    protected function getAutoSort(Model $object): int
    {
        return get_next_sort($object, [
            $this->parentAttr => [
                'operator' => '=',
                'value' => $object->{$this->parentAttr},
            ],
        ]);
    }

    /**
     * @param string $parentId
     * @return void
     */
    protected function setRouteParams(string $parentId): void
    {
        foreach ($this->route as $key => $value) {
            $this->route[$key] = [
                'name' => $value,
                'parameters' => [$this->parentId => $parentId],
            ];
        }
    }
}
