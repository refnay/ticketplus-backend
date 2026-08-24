<?php

namespace App\Catalog\Event\Application\UploadBannerImage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadEventBannerImageCommand
{
    public function __construct( private string $id, private ?UploadedFile $bannerImage)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function bannerImage(): ?UploadedFile
    {
        return $this->bannerImage;
    }
}