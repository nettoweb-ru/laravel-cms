<?php

namespace Netto\Services;

abstract class CDNService
{
    private const DIRECT = [
        'js/ckeditor/ckeditor.js',
        'js/ckeditor/translations/:langId.js',
    ];

    private const FILES = [
        'ckeditor' => [
            'js/ckeditor/ckeditor.js',
            'js/ckeditor/translations/:langId.js',
        ],
        'fancybox' => [
            'css/fancybox/fancybox.css',
            'js/fancybox/5.0.min.js',
        ],
        'font.didact-gothic' => [
            'css/fonts/didact-gothic.css',
        ],
        'font.montserrat' => [
            'css/fonts/montserrat.css',
        ],
        'font.open-sans' => [
            'css/fonts/open-sans.css',
        ],
        'font.play' => [
            'css/fonts/play.css',
        ],
        'font.roboto' => [
            'css/fonts/roboto.css',
        ],
        'jquery' => [
            'js/jquery/3.7.1.min.js',
        ],
        'jquery.longpress' => [
            'js/jquery.longpress/0.1.2.js',
        ],
        'jquery.ui' => [
            'js/jquery.ui/1.13.2.js',
            'css/jquery.ui/1.13.2.css'
        ],
        'jplayer' => [
            'js/jplayer/2.9.2.min.js',
        ],
        'normalize' => [
            'css/normalize/normalize.css',
        ],
    ];

    private const PREPEND = [
        'normalize',
        'jquery',
    ];

    private static array $resources = [];
    private static array $tags;

    /**
     * Load CDN resources by given codes.
     *
     * @param string|array $resources
     * @return void
     */
    public static function load(string|array $resources): void
    {
        self::$resources = array_merge(self::$resources, (array) $resources);
    }

    /**
     * Return HTML tags for loaded resources.
     *
     * @return string
     */
    public static function tags(): string
    {
        self::prepare();
        $return = '';

        foreach (self::$tags as $tags) {
            foreach ($tags as $tag) {
                $return .= $tag.PHP_EOL;
            }
        }

        return $return;
    }

    /**
     * @return void
     */
    private static function prepare(): void
    {
        self::$resources = array_unique(self::$resources);
        self::$tags = [
            'prepend' => [
                '<link rel="preconnect" href="'.NETTO_CDN_URL.'">'
            ],
            'css' => [],
            'js' => [],
        ];

        foreach (self::PREPEND as $prepend) {
            if (in_array($prepend, self::$resources)) {
                foreach (self::FILES[$prepend] as $file) {
                    self::tag($file);
                }

                unset(self::$resources[array_search($prepend, self::$resources)]);
            }
        }

        foreach (self::$resources as $resource) {
            if (!array_key_exists($resource, self::FILES)) {
                continue;
            }

            foreach (self::FILES[$resource] as $file) {
                self::tag($file);
            }
        }
    }

    /**
     * @param string $file
     * @return void
     */
    private static function tag(string $file): void
    {
        $defer = in_array($file, self::DIRECT) ? '' : ' defer';
        if (str_contains($file, ':langId')) {
            $file = str_replace(':langId', app()->getLocale(), $file);
        }

        if (str_starts_with($file, 'js/')) {
            self::$tags['js'][] = '<script'.$defer.' src="'.NETTO_CDN_URL.'/'.$file.'"></script>';
        } else if (str_starts_with($file, 'css/')) {
            self::$tags['css'][] = '<link href="'.NETTO_CDN_URL.'/'.$file.'" rel="stylesheet" type="text/css">';
        }
    }
}
