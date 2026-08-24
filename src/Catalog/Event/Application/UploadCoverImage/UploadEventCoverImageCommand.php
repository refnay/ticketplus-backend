<?php

namespace App\Catalog\Event\Application\UploadCoverImage;

use App\Shared\Application\Input\FileUpload;

class UploadEventCoverImageCommand
{
    public function __construct(
        private string $id,
        private ?FileUpload $coverImage,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function coverImage(): ?FileUpload
    {
        return $this->coverImage;
    }
}
