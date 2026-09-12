<?php

namespace App\Catalog\Event\Application\UploadThumbnail;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class UploadEventThumbnailCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventThumbnailUploader $uploader)
    {
    }

    public function __invoke(UploadEventThumbnailCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->thumbnail(),
            CompanyId::fromString($companyId),
        );
    }
}
