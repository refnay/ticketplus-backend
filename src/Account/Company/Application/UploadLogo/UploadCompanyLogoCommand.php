<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Shared\Application\Input\FileUpload;

class UploadCompanyLogoCommand
{
    public function __construct(private ?FileUpload $logo)
    {
    }

    public function logo(): ?FileUpload
    {
        return $this->logo;
    }
}
