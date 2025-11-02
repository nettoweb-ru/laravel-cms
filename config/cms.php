<?php
return [
    'admin' => 'administrator',
    'captcha' => [
        'bias' => 11,
        'multiplier' => 2,
    ],
    'cdn_host' => 'https://cdn.nettoweb.ru',
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
