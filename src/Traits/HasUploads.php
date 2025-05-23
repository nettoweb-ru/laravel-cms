<?php

namespace Netto\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{File, Storage};
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Netto\Exceptions\NettoException;

trait HasUploads
{
    protected const IMAGE_WIDTH = 150;
    protected const IMAGE_HEIGHT = 150;
    private const IMAGE_QUALITY = 91;

    /**
     * @return void
     */
    protected function checkUploads(): void
    {
        $changes = $this->getChanges();
        $original = $this->getOriginal();
        $basePath = base_path().DIRECTORY_SEPARATOR;
        $delete = [];

        foreach ($this->uploads as $attribute => $params) {
            if (array_key_exists($attribute, $changes) && !empty($original[$attribute])) {
                $delete[] = $basePath.$original[$attribute];

                foreach ($this->uploads as $key => $value) {
                    if (!empty($value['auto']) && ($value['auto'] == $attribute) && !empty($original[$key])) {
                        $delete[] = $basePath.$original[$key];
                    }
                }
            }
        }

        $delete = array_unique($delete);

        if ($delete) {
            File::delete($delete);
        }
    }

    /**
     * @return void
     */
    protected function deleteUploads(): void
    {
        $attributes = $this->getAttributes();
        $basePath = base_path().DIRECTORY_SEPARATOR;
        $delete = [];

        foreach ($this->uploads as $attribute => $params) {
            if (!empty($attributes[$attribute])) {
                $delete[] = $basePath.$attributes[$attribute];
            }
        }

        if ($delete) {
            File::delete($delete);
        }
    }

    /**
     * @return void
     */
    protected function prepareUploads(): void
    {
        foreach ($this->getAttributes() as $attribute => $value) {
            if (is_object($value) && is_a($value, UploadedFile::class) && str_ends_with($attribute, '|new')) {
                $key = substr($attribute, 0, strlen($attribute) - 4);
                $this->setAttribute($key, $value);
                unset($this->{$attribute});
            }
        }
    }

    /**
     * @return void
     * @throws NettoException
     */
    protected function saveUploads(): void
    {
        $uploads = [];
        foreach ($this->getAttributes() as $attribute => $value) {
            if (is_object($value) && is_a($value, UploadedFile::class)) {
                $uploads[$attribute] = $value;
            }
        }

        foreach ($this->uploads as $attribute => $params) {
            if (!empty($params['auto'])) {
                if (!empty($uploads[$params['auto']]) && empty($uploads[$attribute])) {
                    $uploads[$attribute] = $uploads[$params['auto']];
                }

                if (empty($this->getAttribute($params['auto']))) {
                    $this->setAttribute($attribute, null);
                }
            }
        }

        if (empty($uploads)) {
            return;
        }

        $widthDefault = config('cms.image.width', self::IMAGE_WIDTH);
        $heightDefault = config('cms.image.height', self::IMAGE_HEIGHT);
        $qualityDefault = config('cms.image.quality', self::IMAGE_QUALITY);

        $basePath = base_path().DIRECTORY_SEPARATOR;

        foreach ($uploads as $attribute => $upload) {
            $storage = $this->uploads[$attribute]['storage'];
            $disk = Storage::disk($storage);

            if (array_key_exists('width', $this->uploads[$attribute]) && array_key_exists('height', $this->uploads[$attribute])) {
                $width = $this->uploads[$attribute]['width'] ?? $widthDefault;
                $height = $this->uploads[$attribute]['height'] ?? $heightDefault;
                $quality = $this->uploads[$attribute]['quality'] ?? $qualityDefault;

                try {
                    $resized = Image::read($upload)->resize($width, $height);
                    $path = 'auto'.DIRECTORY_SEPARATOR.Str::random(40).'.'.$upload->getClientOriginalExtension();

                    $disk->put(
                        $path,
                        $resized->encodeByExtension(
                            $upload->getClientOriginalExtension(),
                            quality: $quality
                        )
                    );
                } catch (\Exception $exception) {
                    throw new NettoException($exception->getMessage());
                }
            } else {
                $path = $upload->store('auto', $storage);
                if (empty($path)) {
                    throw new NettoException(__('main.error_saving_model'));
                }
            }

            $this->setAttribute($attribute, str_replace($basePath, '', $disk->path('').$path));
        }
    }
}
