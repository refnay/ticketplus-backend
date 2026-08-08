<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Account\Company\Domain\CompanyId;

class UploadCompanyLogoCommandHandler
{
    public function __construct(private CompanyLogoUploader $uploader)
    {
    }

    public function __invoke(UploadCompanyLogoCommand $command): void
    {
        $this->uploader->__invoke(
            CompanyId::fromString($command->session()->company()),
            $command->logo(),
        );
    }
}