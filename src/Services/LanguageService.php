<?php

namespace Netto\Services;

use Netto\Models\Language;

abstract class LanguageService
{
    /**
     * @return int
     */
    public static function getCurrentId(): int
    {
        return self::getList()[app()->getLocale()]['id'];
    }

    /**
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
}
