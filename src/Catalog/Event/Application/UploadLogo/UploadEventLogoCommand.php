<?php

namespace App\Catalog\Event\Application\UploadLogo;

use App\Shared\Application\Input\FileUpload;

class UploadEventLogoCommand
{
    public function __construct(private string $id, private ?FileUpload $logo)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function logo(): ?FileUpload
    {
        return $this->logo;
    }
}
