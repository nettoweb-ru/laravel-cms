<?php
return [
    'admin' => 'administrator',
    'captcha' => [
        'bias' => 11,
        'multiplier' => 2,
    ],
    'default_language' => 'ru',
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
