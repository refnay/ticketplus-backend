<?php

namespace App\Catalog\Event\Application\UploadCoverImage;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class UploadEventCoverImageCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventCoverImageUploader $uploader)
    {
    }

    public function __invoke(UploadEventCoverImageCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->coverImage(),
            CompanyId::fromString($companyId),
        );
    }
}