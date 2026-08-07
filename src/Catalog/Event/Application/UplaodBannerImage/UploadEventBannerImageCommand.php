<?php

namespace App\Catalog\Event\Application\UplaodBannerImage;

use App\Shared\Application\Command\BaseCommand;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadEventBannerImageCommand extends BaseCommand
{
    public function __construct(
        private string $id,
        private ?UploadedFile $bannerImage
    ) {
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