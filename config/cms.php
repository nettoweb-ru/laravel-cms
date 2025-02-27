<?php
return [
    'location' => 'admin',
    'default_language' => 'en',
    'locales' => [],
    'title_separator' => ' | ',
    'album_max_width' => 150,
    'album_max_height' => 150,
    'default_email' => '',
    'service_email' => '',
    'timezone' => 'UTC',
    'cdn' => [
        'url' => '//cdn.nettoweb.ru',
        'files' => [
            'ckeditor' => [
                'js/ckeditor/ckeditor.js',
                'js/ckeditor/translations/:langId.js',
            ],
            'jquery' => [
                'js/jquery/3.7.1.min.js',
            ],
            'longpress' => [
                'js/jquery.longpress/0.1.2.js',
            ],
            'ui' => [
                'js/jquery.ui/1.13.2.js',
            ],
            'normalize' => [
                'css/normalize/normalize.css',
            ],
            'play' => [
                'css/fonts/play.css',
            ],
        ],
    ],
];
