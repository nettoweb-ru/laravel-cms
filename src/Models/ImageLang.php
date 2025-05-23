<?php

namespace Netto\Models;

use Netto\Models\Abstract\Pivot as BaseModel;

class ImageLang extends BaseModel
{
    public $timestamps = false;
    public $table = 'cms__images__lang';

    protected string $parentClass = Image::class;
}
