<?php

namespace Netto\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
     * @param string $path
     * @param string $storage
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public static function imageResize(string $path, string $storage, ?int $width = null, ?int $height = null): string
    {
        if (is_null($width)) {
            $width = config('cms.album_max_width');
        }

        if (is_null($height)) {
            $height = config('cms.album_max_height');
        }

        $basePath = base_path().DIRECTORY_SEPARATOR;
        $manager = new ImageManager(Driver::class);

        $image = $manager->read($basePath.$path);

        if ($imageSize = getimagesize($basePath.$path)) {
            if (($imageSize[0] <= $width) && ($imageSize[1] <= $height)) {
                return $path;
            }
        }

        $tmp = tempnam('/tmp', 'resize');
        $image->cover($width, $height)->save($tmp, 95);
        $file = new UploadedFile($tmp, basename($tmp));

        $resized = $file->store('auto', $storage);
        $disk = Storage::disk($storage);

        return str_replace($basePath, '', $disk->path('').$resized);
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
