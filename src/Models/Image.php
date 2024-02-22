<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Netto\Services\CmsService;
use Netto\Traits\HasMultiLangAttributes;
use Netto\Traits\HasUploadedFiles;

class Image extends Model
{
    use HasMultiLangAttributes, HasUploadedFiles;

    public $timestamps = false;
    public $table = 'cms__images';

    protected $attributes = [
        'sort' => 0,
    ];

    protected array $multiLang = [
        'caption',
    ];

    protected string $multiLangClass = ImageLang::class;

    protected array $files = [
        'filename' => 'public',
        'thumb' => 'public',
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::saving(function($model) {
            $model->setAttribute('thumb', CmsService::imageResize($model->filename, $model->files['thumb']));
        });

        self::updated(function($model) {
            $model->checkUpdatedFiles();
        });

        self::deleted(function($model) {
            $model->checkDeletedFiles();
        });
    }

    /**
     * @return BelongsTo
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->exists
            ? '/storage/auto/'.basename($this->thumb)
            : '';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->exists
            ? '/storage/auto/'.basename($this->filename)
            : '';
    }
}
