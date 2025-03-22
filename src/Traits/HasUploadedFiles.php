<?php

namespace Netto\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ViewErrorBag;

trait HasUploadedFiles
{
    /**
     * @param string $attribute
     * @return string
     */
    public function getStorageCode(string $attribute): string
    {
        return $this->files[$attribute];
    }

    /**
     * @param ViewErrorBag $errors
     * @param string $attribute
     * @return array
     */
    public function getUploadErrors(ViewErrorBag $errors, string $attribute): array
    {
        return array_merge($errors->get($attribute), $errors->get($attribute.'_new'));
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function saveUploaded(array &$attributes): bool
    {
        if (empty($this->files)) {
            return true;
        }

        $upload = [];
        foreach ($this->files as $attribute => $storage) {
            $key = $attribute.'_new';
            if (array_key_exists($key, $attributes)) {
                $upload[$storage][$attribute] = [
                    0 => $attributes[$key],
                    1 => $key,
                ];
            }
        }

        if (empty($upload)) {
            return true;
        }

        $basePath = base_path().DIRECTORY_SEPARATOR;

        foreach ($upload as $storage => $files) {
            $disk = Storage::disk($storage);

            foreach ($files as $attribute => $value) {
                [$file, $key] = $value;

                $path = $file->store('auto', $storage);
                if (empty($path)) {
                    return false;
                }

                /** @var UploadedFile $file */
                $attributes[$attribute] = str_replace($basePath, '', $disk->path('').$path);
                unset($attributes[$key]);
            }
        }

        return true;
    }

    /**
     * @return void
     */
    private function checkDeletedFiles(): void
    {
        $delete = [];
        foreach ($this->files as $attribute => $storage) {
            if ($this->{$attribute}) {
                $delete[] = base_path().DIRECTORY_SEPARATOR.$this->{$attribute};
            }
        }

        if ($delete) {
            File::delete($delete);
        }
    }

    /**
     * @return void
     */
    private function checkUpdatedFiles(): void
    {
        $delete = [];
        foreach ($this->files as $attribute => $storage) {
            if (array_key_exists($attribute, $this->changes) && !empty($this->original[$attribute])) {
                $delete[] = base_path().DIRECTORY_SEPARATOR.$this->original[$attribute];
            }
        }

        if ($delete) {
            File::delete($delete);
        }
    }
}
