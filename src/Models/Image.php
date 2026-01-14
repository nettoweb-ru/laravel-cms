<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Models\Abstract\Model as BaseModel;
use Netto\Traits\{HasUploads, IsMultiLingual};

/**
 * @property Album $album
 */

class Image extends BaseModel
{
    use HasUploads, IsMultiLingual;

    public $timestamps = false;
    public $table = 'cms__images';

    public array $multiLingual = [
        'caption',
    ];

    public string $multiLingualClass = ImageLang::class;

    public array $uploads = [
        'filename' => [
            'storage' => 'public',
        ],
        'thumb' => [
            'storage' => 'public',
            'width' => null,
            'height' => null,
            'auto' => 'filename',
            'square' => true,
        ],
    ];

    protected $attributes = [
        'sort' => 0,
    ];

    /**
     * @return BelongsTo
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
