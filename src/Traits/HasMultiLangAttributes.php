<?php

namespace Netto\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\ViewErrorBag;
use Netto\Models\Language;
use Netto\Services\LanguageService;

/**
 * @property Collection $translated
 */

trait HasMultiLangAttributes
{
    /**
     * @return array
     */
    public function getMultiLangAttributes(): array
    {
        $return = [];
        foreach (LanguageService::getList() as $lang => $value) {
            foreach ($this->multiLang as $attribute) {
                $return[] = "{$attribute}|{$lang}";
            }
        }

        return $return;
    }

    /**
     * @param string $name
     * @param string|null $lang
     * @return string|array|null
     */
    public function getMultiLangAttributeValue(string $name, ?string $lang = null): string|array|null
    {
        if (in_array($name, $this->multiLang)) {
            if (is_null($lang)) {
                $return = $this->empty();
                if (!$this->exists) {
                    return $return;
                }

                foreach ($this->translated->all() as $item) {
                    $return[$item->slug] = $item->pivot->{$name};
                }

                return $return;
            }

            if ($this->exists) {
                foreach ($this->translated->all() as $item) {
                    if ($item->slug == $lang) {
                        return (string) $item->pivot->{$name};
                    }
                }
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getMultiLangClass(): string
    {
        return $this->multiLangClass;
    }

    /**
     * @param ViewErrorBag $errorBag
     * @param string $attribute
     * @return array
     */
    public function getMultiLangInputErrors(ViewErrorBag $errorBag, string $attribute): array
    {
        $return = [];
        if (!in_array($attribute, $this->multiLang)) {
            return $return;
        }

        foreach (LanguageService::getList() as $lang => $value) {
            $return[$lang] = ($errors = $errorBag->get("{$attribute}|{$lang}")) ? $errors : [];
        }

        return $return;
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function getMultiLangOldValue(string $attribute): array
    {
        $return = [];
        if (!in_array($attribute, $this->multiLang)) {
            return $return;
        }

        $old = $this->getMultiLangAttributeValue($attribute);

        foreach (LanguageService::getList() as $lang => $value) {
            $return[$lang] = old("{$attribute}|{$lang}", $old[$lang]);
        }

        return $return;
    }

    /**
     * @param array $rules
     * @return array
     */
    public function getMultiLangRules(array $rules): array
    {
        $return = [];
        foreach (LanguageService::getList() as $lang => $value) {
            foreach ($rules as $attribute => $array) {
                $return["{$attribute}|{$lang}"] = $array;
            }
        }

        return $return;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function saveMultiLang(array $attributes): bool
    {
        $attr = [];
        foreach (LanguageService::getList() as $lang => $value) {
            foreach ($this->multiLang as $attribute) {
                $attr[] = "{$attribute}|{$lang}";
            }
        }

        $default = LanguageService::getDefaultCode();

        $inputBase = [];
        $inputLang = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $attr)) {
                $inputLang[substr($key, -2, 2)][substr($key, 0, (strlen($key) - 3))] = $value;
            } else {
                $inputBase[$key] = $value;
            }
        }

        if (isset($inputLang[$default]['title'])) {
            $inputBase['name'] = $inputLang[$default]['title'];
        }

        foreach ($inputBase as $key => $value) {
            $this->setAttribute($key, $value);
        }

        if (!$this->save()) {
            return false;
        }

        $models = [];
        foreach ($this->translated->all() as $item) {
            $models[$item->slug] = $item->pivot;
        }

        foreach (LanguageService::getList() as $code => $language) {
            if (!array_key_exists($code, $models)) {
                /** @var Model $model */
                $model = new $this->multiLangClass();
                $model->setAttribute('lang_id', $language['id']);
                $model->setAttribute('object_id', $this->id);

                $models[$code] = $model;
            }
        }

        foreach ($inputLang as $code => $input) {
            foreach ($input as $key => $value) {
                $models[$code]->setAttribute($key, $value);
            }

            if (!$models[$code]->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return BelongsToMany
     */
    public function translated(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, $this->multiLangClass, 'object_id', 'lang_id')->withPivot($this->multiLang)->using($this->multiLangClass);
    }

    /**
     * @return array
     */
    private function empty(): array
    {
        static $return;

        if (is_null($return)) {
            $return = [];
            foreach (LanguageService::getList() as $key => $value) {
                $return[$key] = '';
            }
        }

        return $return;
    }
}
