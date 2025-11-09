<?php
return [
    'admin' => 'administrator',
    'assets' => [
        'host' => 'https://cdn.nettoweb.ru',
        'files' => [
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
        'prepend' => [
            'normalize',
            'jquery',
        ],
    ],
    'captcha' => [
        'bias' => 11,
        'multiplier' => 2,
    ],
    'default_language' => 'ru',
    'export' => [
        'csv' => [
            'separator' => ',',
            'enclosure' => '"',
            'escape' => "\\",
            'eol' => PHP_EOL,
            'encoding' => '',
        ],
    ],
    'image' => [
        'width' => 150,
        'height' => 150,
        'quality' => 91,
    ],
    'locales' => [],
    'location' => 'admin',
    'logs' => [
        'read' => [
            'max' => 10,
            'files' => [
                'laravel.log',
                'sent.log',
            ],
        ],
        'send' => [
            'email' => '',
            'files' => [
                'laravel.log',
            ],
        ],
    ],
    'schedule' => [
        'hourly' => 0,
        'daily' => 1,
        'weekly' => 2
    ],
    'title_separator' => ' | ',
    'utf8suffix' => 'utf8',
];
