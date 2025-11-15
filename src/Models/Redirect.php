<?php

declare(strict_types=1);

namespace Netto\Models;

use Netto\Models\Abstract\Model as BaseModel;

class Redirect extends BaseModel
{
    public $table = 'cms__redirects';
    public $timestamps = false;

    protected $attributes = [
        'is_active' => '0',
        'is_regexp' => '0',
        'status' => 301,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_regexp' => 'boolean',
    ];
}
