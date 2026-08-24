<?php

namespace App\Catalog\Event\Application\UploadCoverImage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadEventCoverImageCommand
{
    public function __construct(
        private string $id,
        private ?UploadedFile $coverImage,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function coverImage(): ?UploadedFile
    {
        return $this->coverImage;
    }
}