<?php

namespace Netto\Handlers\Abstract;

use Illuminate\Http\UploadedFile;

abstract class FileHandler
{
    protected array $tmpFiles = [];
    protected UploadedFile $result;
    protected UploadedFile $file;

    public function __construct(UploadedFile $file) {
        $this->file = $file;
    }

    public function getTmpFiles(): array
    {
        return $this->tmpFiles;
    }

    abstract public function result(): UploadedFile;
}
