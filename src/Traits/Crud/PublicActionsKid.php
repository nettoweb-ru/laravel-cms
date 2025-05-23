<?php

namespace Netto\Traits\Crud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;

use Netto\Models\Abstract\Model;

trait PublicActionsKid
{
    /**
     * @param string $parentId
     * @param string $id
     * @return Response
     */
    public function kid(string $parentId, string $id): Response
    {
        return $this->view($this->viewId['kid'], $this->getKidData($parentId, $id));
    }

    /**
     * @param Model $parent
     * @return Builder
     */
    protected function getBuilderKid(Model $parent): Builder
    {
        /** @var HasMany $relation */
        $relation = $parent->{$this->parentRelation}();
        $object = $relation->getRelated();

        $return = $object->newQuery()->where($relation->getForeignKeyName(), $parent->getAttribute('id'));

        if ($object->hasAttribute('is_active')) {
            $return->where('is_active', '1');
        }

        return $return;
    }

    /**
     * @param array $params
     * @return string
     */
    abstract protected function getItemBackLinkKid(array $params = []): string;

    /**
     * @param string $id
     * @return array
     */
    protected function getItemData(string $id): array
    {
        $model = $this->getParent($id);

        $this->addTitle($model->name);
        $this->addCrumb($model->name);

        $header = __($this->listTitle);
        $this->addTitle($header);
        $this->addCrumb($header, $this->getItemBackLink());

        $return = $this->getModelData($model);
        $return['content']['back'] = ''; // @TODO

        $this->prepareItem($model, $return);

        $builder = $this->getBuilderKid($model);
        $builder->with($this->kidRelation);
        $this->applyListParams($builder, $this->listKidParams);

        $page = $this->validatePage(request()->query('page'));

        $collection = $builder->pluck('id');

        if ($this->listKidPerPage) {
            $id = $collection->slice(($page - 1) * $this->listKidPerPage, $this->listKidPerPage)->all();
            $max = (new LengthAwarePaginator($id, $collection->count(), $this->listKidPerPage, $page))->lastPage();

            $builder->whereIn('id', $id);
        } else {
            $max = 1;
        }

        if ($page > $max) {
            abort(404);
        }

        $items = [];
        foreach ($builder->get() as $item) {
            /** @var Model $item */
            $items[] = $this->prepareListKidItem($item);
        }

        $return['list'] = [
            'items' => $items,
            'navigation'=> [
                'max' => $max,
                'current' => $page,
                'link' => $return['head']['canonical']
            ],
        ];

        if ($page > 1) {
            $return['head']['canonical'] .= "?page={$page}";
        }

        $return['content']['back'] = $this->getItemBackLink($this->getBackParams($model->getAttribute('id')));

        return $return;
    }

    /**
     * @param string $parentId
     * @param string $id
     * @return array
     */
    protected function getKidData(string $parentId, string $id): array
    {
        $model = $this->getParent($parentId);
        $builder = $this->getBuilderKid($model);

        /** @var Model $kid */
        $kid = $builder->where($this->kidKey, $id)->first();
        if (is_null($kid)) {
            abort(404);
        }

        $this->addTitle($kid->name);
        $this->addCrumb($kid->name);

        $this->addTitle($model->name);
        $this->addCrumb($model->name, $this->getItemBackLinkKid(['item' => $parentId]));

        $header = __($this->listTitle);
        $this->addTitle($header);
        $this->addCrumb($header, $this->getItemBackLink());

        $return = $this->getModelData($kid);
        $this->prepareKid($model, $return);

        $builder = $this->getBuilderKid($model);
        $this->applyListParams($builder, $this->listKidParams);

        $backParams = ['item' => $parentId];
        $backPage = $this->getBackPage($builder, $this->listKidPerPage, $kid->getAttribute('id'));

        if ($backPage > 1) {
            $backParams['page'] = $backPage;
        }

        $return['content']['back'] = $this->getItemBackLinkKid($backParams);

        return $return;
    }

    /**
     * @param string $id
     * @return Model
     */
    protected function getParent(string $id): Model
    {
        /** @var Model $object */
        $object = new $this->className();
        $builder = $object->newQuery();

        if ($object->hasAttribute('is_active')) {
            $builder->where('is_active', '1');
        }

        $return = $builder->where($this->itemKey, $id)->first();
        if (is_null($return)) {
            abort(404);
        }

        return $return;
    }

    /**
     * @param Model $model
     * @param array $return
     * @return void
     */
    protected function prepareKid(Model $model, array &$return): void
    {

    }

    /**
     * @param Model $model
     * @return array
     */
    protected function prepareListKidItem(Model $model): array
    {
        return $model->toArray();
    }
}
