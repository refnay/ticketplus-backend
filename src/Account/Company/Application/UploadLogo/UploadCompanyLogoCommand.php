<?php

namespace App\Account\Company\Application\UploadLogo;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadCompanyLogoCommand
{
    public function __construct(private ?UploadedFile $logo)
    {
    }

    public function logo(): ?UploadedFile
    {
        return $this->logo;
    }
}