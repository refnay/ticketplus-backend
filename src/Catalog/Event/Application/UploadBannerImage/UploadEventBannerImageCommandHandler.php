<?php

namespace App\Catalog\Event\Application\UploadBannerImage;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class UploadEventBannerImageCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventBannerImageUploader $uploader)
    {
    }

    public function __invoke(UploadEventBannerImageCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->bannerImage(),
            CompanyId::fromString($companyId),
        );
    }
}