<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\Company\Domain\CompanyId;

class UploadCompanyLogoCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private CompanyLogoUploader $uploader)
    {
    }

    public function __invoke(UploadCompanyLogoCommand $command): void
    {
        $this->authorization->validateOwner();

        $this->uploader->__invoke(
            CompanyId::fromString($this->authorization->companyId()),
            $command->logo(),
        );
    }
}
