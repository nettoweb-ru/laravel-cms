<?php

namespace Netto\Handlers;

use Illuminate\Http\UploadedFile;
use Netto\Handlers\Abstract\ImageHandler;

class AlbumPreviewHandler extends ImageHandler
{
    public function __construct(UploadedFile $file) {
        parent::__construct($file);

        $this->width = config('cms.album-max-width-preview');
        $this->height = config('cms.album-max-height-preview');
    }

    public function result(): UploadedFile
    {
        $this->coverDown();
        return $this->result;
    }
}
