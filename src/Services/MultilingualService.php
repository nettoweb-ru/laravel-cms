<?php

namespace Netto\Services;

use Netto\Models\Language;

abstract class MultilingualService
{
    /**
     * Return ID of current public language. Should be called on public pages only.
     *
     * @return int
     */
    public static function getCurrentId(): int
    {
        return self::getList()[app()->getLocale()]['id'];
    }

    /**
     * Return slug of default public language.
     *
     * @return string
     */
    public static function getDefaultCode(): string
    {
        static $return;

        if (is_null($return)) {
            foreach (self::getList() as $code => $language) {
                if ($language['is_default']) {
                    $return = $code;
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * Return ID of default public language.
     *
     * @return int
     */
    public static function getDefaultId(): int
    {
        static $return;

        if (is_null($return)) {
            foreach (self::getList() as $item) {
                if ($item['is_default']) {
                    $return = $item['id'];
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * Returns language slug by ID.
     *
     * @param int $id
     * @return string|null
     */
    public static function getLanguageCode(int $id): ?string
    {
        foreach (get_language_list() as $slug => $language) {
            if ($language['id'] == $id) {
                return $slug;
            }
        }

        return null;
    }

    /**
     * Return the list of public languages.
     *
     * @return array
     */
    public static function getList(): array
    {
        static $return;

        if (is_null($return)) {
            $return = [];

            foreach (Language::all() as $item) {
                $return[$item->slug] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'locale' => $item->locale,
                    'is_default' => $item->is_default,
                ];
            }
        }

        return $return;
    }

    /**
     * Convert the list of rules to multilingual rules.
     *
     * @param array $rules
     * @return array
     */
    public static function getRules(array $rules): array
    {
        $return = [];
        foreach (self::getList() as $lang => $value) {
            foreach ($rules as $attribute => $array) {
                $return["{$attribute}|{$lang}"] = $array;
            }
        }

        return $return;
    }

    /**
     * Sets default public language as application language.
     *
     * @return void
     */
    public static function setDefaultLanguage(): void
    {
        $language = self::getDefaultCode();

        $locales = array_map(function ($value) {
            return $value['locale'];
        }, self::getList());

        set_language($language, $locales[$language]);
    }
}
