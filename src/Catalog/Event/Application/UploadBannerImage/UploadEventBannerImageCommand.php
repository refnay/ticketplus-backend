<?php

namespace App\Catalog\Event\Application\UploadBannerImage;

use App\Shared\Application\Input\FileUpload;

class UploadEventBannerImageCommand
{
    public function __construct(private string $id, private ?FileUpload $bannerImage)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function bannerImage(): ?FileUpload
    {
        return $this->bannerImage;
    }
}
