<?php

namespace App\Catalog\Event\Application\UploadLogo;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class UploadEventLogoCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventLogoUploader $uploader)
    {
    }

    public function __invoke(UploadEventLogoCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->logo(),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
