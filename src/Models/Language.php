<?php

namespace Netto\Models;

use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\HasDefaultAttribute;

class Language extends BaseModel
{
    use HasDefaultAttribute;

    public $timestamps = false;
    public $table = 'cms__lang';

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $attributes = [
        'sort' => 0,
        'is_default' => '0',
    ];
}
