<?php

namespace Netto\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

abstract class CmsService
{
    /**
     * @return array
     */
    public static function getBooleanLabels(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [1 => __('cms::main.general_yes'), 0 => __('cms::main.general_no')];
        }

        return $return;
    }

    /**
     * @param string $class
     * @param string $column
     * @param bool $emptyLead
     * @return array
     */
    public static function getModelLabels(string $class, string $column = 'name', bool $emptyLead = false): array
    {
        $return = [];
        if ($emptyLead) {
            $return[''] = '';
        }

        /** @var Builder $class */
        $builder = new $class();
        foreach ($builder->select($column, 'id')->orderBy($column)->get() as $model) {
            $return[$model->id] = $model->{$column};
        }

        return $return;
    }

    /**
     * @param Model $model
     * @param array $filter
     * @return int
     */
    public static function getModelSort(Model $model, array $filter = []): int
    {
        $return = 0;

        $builder = $model->select('sort')->orderBy('sort', 'desc');
        self::setBuilderFilter($builder, $filter);

        if (count($builder->get()) > 0) {
            $item = $builder->get()->get(0);
            $return = $item->sort;
        }

        $return += 10;
        return $return;
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public static function setAdminCookie(string $name, string $value): void
    {
        Cookie::queue(Cookie::forever($name, $value, '/'.CMS_LOCATION));
    }

    /**
     * @param Builder $builder
     * @param array $filter
     * @return void
     */
    public static function setBuilderFilter(Builder &$builder, array $filter): void
    {
        if (empty($filter)) {
            return;
        }

        foreach ($filter as $key => $value) {
            if (str_contains($key, '.')) {
                $tmp = explode('.', $key);
                $builder->whereHas($tmp[0], function($builder) use ($tmp, $value) {
                    unset($tmp[0]);
                    self::setBuilderFilter($builder, [
                        implode('.', $tmp) => $value,
                    ]);
                });
            } else {
                if (is_null($value['value'])) {
                    $builder->whereNull($key);
                } else {
                    if (is_array($value['value'])) {
                        $builder->whereIn($key, $value['value']);
                    } else {
                        $builder->where($key, $value['operator'], $value['value']);
                    }
                }
            }
        }
    }
}
