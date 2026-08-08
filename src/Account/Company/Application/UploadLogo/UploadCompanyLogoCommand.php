<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Shared\Application\Command\BaseCommand;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadCompanyLogoCommand extends BaseCommand
{
    public function __construct(private ?UploadedFile $logo)
    {
    }

    public function logo(): ?UploadedFile
    {
        return $this->logo;
    }
}