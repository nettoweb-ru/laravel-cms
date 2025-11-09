<?php

declare(strict_types=1);

namespace Netto\Services;

abstract class AssetService
{
    private static array $assets = [];
    private static ?array $structure = null;

    /**
     * @return array
     */
    public static function css(): array
    {
        if (is_null(self::$structure)) {
            self::init();
        }

        return self::$structure['css'];
    }

    /**
     * @return array
     */
    public static function js(): array
    {
        if (is_null(self::$structure)) {
            self::init();
        }

        return self::$structure['js'];
    }

    /**
     * @param string|array $assets
     * @param bool $defer
     * @return void
     */
    public static function load(string|array $assets, bool $defer = true): void
    {
        foreach ((array) $assets as $asset) {
            if (array_key_exists($asset, self::$assets) && !self::$assets[$asset]) {
                continue;
            }

            self::$assets[$asset] = $defer;
        }
    }

    /**
     * @return void
     */
    private static function init(): void
    {
        self::$structure = [
            'css' => [],
            'js' => [],
        ];

        $assets = [];
        foreach (config('cms.assets.prepend', []) as $prepend) {
            if (array_key_exists($prepend, self::$assets)) {
                $assets[$prepend] = self::$assets[$prepend];
                unset(self::$assets[$prepend]);
            }
        }

        $assets += self::$assets;

        $files = config('cms.assets.files', []);
        $lang = app()->getLocale();

        foreach ($assets as $asset => $deferred) {
            foreach ($files[$asset] ?? [] as $file) {
                if (str_contains($file, ':langId')) {
                    $file = str_replace(':langId', $lang, $file);
                }

                if (str_starts_with($file, 'js/')) {
                    self::$structure['js'][$file] = $deferred;
                } else if (str_starts_with($file, 'css/')) {
                    self::$structure['css'][$file] = $deferred;
                }
            }
        }
    }
}
