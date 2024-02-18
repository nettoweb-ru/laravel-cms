<?php

namespace Netto\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
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
            if (empty($model->original['filename']) || ($model->filename != $model->original['filename'])) {
                $manager = new ImageManager(Driver::class);
                $object = new self();

                $tmp = tempnam('/tmp', 'thumb');
                $basePath = base_path().DIRECTORY_SEPARATOR;

                $image = $manager->read($basePath.$model->filename);
                $image->cover(config('cms.album_max_width'), config('cms.album_max_height'))->save($tmp);

                $disk = Storage::disk($object->files['thumb']);
                $file = new UploadedFile($tmp, basename($tmp));
                $path = $file->store('auto', $object->files['thumb']);

                $model->thumb = str_replace($basePath, '', $disk->path('').$path);
                File::delete($tmp);
            }
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
