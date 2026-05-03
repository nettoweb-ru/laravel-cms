<?php

namespace Netto\Handlers\Abstract;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Interfaces\{EncodedImageInterface, ImageInterface};
use Intervention\Image\Laravel\Facades\Image;

abstract class ImageHandler extends FileHandler
{
    protected ?int $width = null;
    protected ?int $height = null;
    protected int $quality;

    protected ImageInterface $image;

    public function __construct(UploadedFile $file) {
        parent::__construct($file);

        $this->quality = config('cms.album-image-quality');
        $this->image = Image::read($this->file);
    }

    protected function coverDown(): void
    {
        $this->saveImage(
            $this->image->coverDown($this->width, $this->height)->encodeByExtension(
                $this->file->getClientOriginalExtension(),
                quality: $this->quality
            )
        );
    }

    protected function scaleDown(): void
    {
        $this->saveImage(
            $this->image->scaleDown($this->width, $this->height)->encodeByExtension(
                $this->file->getClientOriginalExtension(),
                quality: $this->quality
            )
        );
    }

    protected function saveImage(EncodedImageInterface|ImageInterface $processed): void
    {
        $tmpName = tempnam('/tmp', '');
        $processed->save($tmpName);

        $this->result = new UploadedFile(
            $tmpName,
            $this->file->getClientOriginalName(),
            $this->file->getClientMimeType(),
            null,
            true
        );

        $this->tmpFiles[] = $tmpName;
    }
}
