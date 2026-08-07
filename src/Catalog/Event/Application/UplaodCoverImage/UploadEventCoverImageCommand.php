<?php

namespace App\Catalog\Event\Application\UplaodCoverImage;

use App\Shared\Application\Command\BaseCommand;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadEventCoverImageCommand extends BaseCommand
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