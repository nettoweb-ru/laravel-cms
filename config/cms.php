<?php
return [
    'admin-default-language' => 'en',
    'admin-email' => env('NETTO_ADMIN_EMAIL', ''),
    'admin-locales' => [],
    'admin-location' => 'admin',
    'admin-role' => 'administrator',
    'album-image-quality' => 91,
    'album-max-height' => null,
    'album-max-height-preview' => 120,
    'album-max-width' => null,
    'album-max-width-preview' => 120,
    'assets-files' => [
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
    ],
    'assets-host' => 'https://cdn.nettoweb.ru',
    'assets-prepend' => [
        'normalize',
        'jquery',
    ],
    'captcha-bias' => 11,
    'captcha-multiplier' => 2,
    'export-csv-enclosure' => '"',
    'export-csv-encoding' => '',
    'export-csv-eol' => PHP_EOL,
    'export-csv-escape' => "\\",
    'export-csv-separator' => ',',
    'logs-read-files' => [
        'laravel.log',
    ],
    'logs-read-max' => 10,
    'logs-send-email' => env('NETTO_LOG_EMAIL', ''),
    'logs-send-files' => [
        'laravel.log',
    ],
    'logs-track' => [],
    'public-auto-upload-dir' => 'auto',
    'redirects-allowed-3x' => [301, 302, 303, 307, 308],
    'redirects-allowed-4x' => [403, 410],
    'redirects-enable-https' => env('NETTO_REDIRECT_ENABLE_HTTPS', false),
    'redirects-enable-www' => env('NETTO_REDIRECT_ENABLE_WWW', false),
    'redirects-cache-time' => 3600,
    'search-cache-time' => 3600,
    'search-highlight-class' => 'search-highlight',
    'search-max-preview-length' => 256,
    'search-min-query-length' => 3,
    'search-reindex-delay' => 1000000,
    'title-separator' => ' | ',
    'utf8-suffix' => 'utf8',
];
