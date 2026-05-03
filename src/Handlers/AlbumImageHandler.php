<?php

namespace Netto\Handlers;

use Illuminate\Http\UploadedFile;
use Netto\Handlers\Abstract\ImageHandler;

class AlbumImageHandler extends ImageHandler
{
    public function __construct(UploadedFile $file) {
        parent::__construct($file);

        $this->width = config('cms.album-max-width');
        $this->height = config('cms.album-max-height');
    }

    public function result(): UploadedFile
    {
        if ($this->width && $this->height && (($this->image->width() > $this->width) || ($this->image->height() > $this->height))) {
            $this->scaleDown();
        } else {
            $this->saveImage($this->image);
        }

        return $this->result;
    }
}
