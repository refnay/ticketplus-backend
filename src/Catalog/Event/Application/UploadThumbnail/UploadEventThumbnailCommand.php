<?php

namespace App\Catalog\Event\Application\UploadThumbnail;

use App\Shared\Application\Input\FileUpload;

class UploadEventThumbnailCommand
{
    public function __construct(private string $id, private ?FileUpload $thumbnail)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function thumbnail(): ?FileUpload
    {
        return $this->thumbnail;
    }
}
