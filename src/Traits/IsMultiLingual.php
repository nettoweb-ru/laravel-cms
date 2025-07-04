<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\{Collection, Model, Relations\BelongsToMany};
use Netto\Exceptions\NettoException;
use Netto\Models\Language;

/**
 * @property Collection $translated
 */

trait IsMultiLingual
{
    protected array $multiLingualSaveData = [];

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (in_array($name, $this->multiLingual)) {
            if ($this->exists) {
                $lang = app()->getLocale();

                foreach ($this->translated->all() as $item) {
                    if ($item->slug == $lang) {
                        return (string) $item->pivot->{$name};
                    }
                }
            }

            return null;
        }

        return parent::__get($name);
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function getTranslated(string $attribute): array
    {
        if (!in_array($attribute, $this->multiLingual)) {
            return [];
        }

        $return = [];
        foreach (get_language_list() as $key => $value) {
            $return[$key] = '';
        }

        if ($this->exists) {
            foreach ($this->translated->all() as $item) {
                $return[$item->slug] = $item->pivot->{$attribute};
            }
        }

        return $return;
    }

    /**
     * @return BelongsToMany
     */
    public function translated(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, $this->multiLingualClass, 'object_id', 'lang_id')->withPivot($this->multiLingual)->using($this->multiLingualClass);
    }

    /**
     * @return void
     */
    protected function prepareMultiLingual(): void
    {
        if (empty($this->multiLingual)) {
            return;
        }

        foreach ($this->translated->all() as $item) {
            /** @var Language $item */
            foreach ($this->multiLingual as $column) {
                $this->multiLingualSaveData[$item->getAttribute('slug')][$column] = $item->pivot->getAttribute($column);
            }
        }

        $attributes = $this->toArray();

        foreach (get_language_list() as $code => $language) {
            foreach ($this->multiLingual as $column) {
                $key = "{$column}|{$code}";
                if (array_key_exists($key, $attributes)) {
                    $this->multiLingualSaveData[$code][$column] = $attributes[$key];
                    unset($this->{$key});
                }
            }
        }
    }

    /**
     * @return void
     * @throws NettoException
     */
    protected function saveMultiLingual(): void
    {
        if (empty($this->multiLingualSaveData)) {
            return;
        }

        $models = [];
        foreach ($this->translated->all() as $item) {
            $models[$item->slug] = $item->pivot;
        }

        foreach (get_language_list() as $code => $language) {
            if (!array_key_exists($code, $models)) {
                /** @var Model $model */
                $model = new $this->multiLingualClass();
                $model->setAttribute('lang_id', $language['id']);
                $model->setAttribute('object_id', $this->id);

                $models[$code] = $model;
            }
        }

        foreach ($this->multiLingualSaveData as $code => $data) {
            foreach ($data as $key => $value) {
                $models[$code]->setAttribute($key, $value);
            }

            if (!$models[$code]->save()) {
                throw new NettoException(session('status', __('main.error_saving_model')));
            }
        }
    }
}
