<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ImageLang extends Pivot
{
    public $timestamps = false;
    public $table = 'cms__images_lang';
}
