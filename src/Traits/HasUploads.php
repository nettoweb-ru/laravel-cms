<?php

namespace Netto\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Netto\Exceptions\NettoException;
use Netto\Handlers\Abstract\FileHandler;
use Throwable;

trait HasUploads
{
    /**
     * @return void
     */
    protected function checkUploads(): void
    {
        $changes = $this->getChanges();
        $original = $this->getOriginal();
        $delete = [];

        foreach ($this->uploads as $attribute => $params) {
            if (array_key_exists($attribute, $changes) && !empty($original[$attribute])) {
                $delete[] = get_storage_path($original[$attribute], $params['disk']);
            }
        }

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
        $delete = [];

        foreach ($this->uploads as $attribute => $params) {
            if (!empty($attributes[$attribute])) {
                $delete[] = get_storage_path($attributes[$attribute], $params['disk']);
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
        $uploads = array_filter($this->getAttributes(), function ($value) {
            return is_object($value) && is_a($value, UploadedFile::class);
        });

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

        $delete = [];
        $autoDir = config('cms.public-auto-upload-dir');

        foreach ($uploads as $attribute => $upload) {
            $params = $this->uploads[$attribute];

            /** @var UploadedFile $upload */
            if (!empty($params['handlers'])) {
                foreach ($params['handlers'] as $handlerName) {
                    /** @var FileHandler $handler */
                    $handler = new $handlerName($upload);

                    try {
                        $upload = $handler->result();
                    } catch (Throwable $exception) {
                        throw new NettoException($exception->getMessage());
                    }

                    $delete = array_merge($delete, $handler->getTmpFiles());
                }
            }

            $path = $upload->store($autoDir, $params['disk']);

            if ($path === false) {
                throw new NettoException(__('main.error_saving_model'));
            }

            $this->setAttribute($attribute, $path);
        }

        if ($delete) {
            File::delete($delete);
        }
    }
}
