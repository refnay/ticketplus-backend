<?php

namespace App\Catalog\Event\Application\UplaodCoverImage;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class UploadEventCoverImageCommandHandler
{
    public function __construct(private EventCoverImageUploader $uploader)
    {
    }

    public function __invoke(UploadEventCoverImageCommand $command): void
    {
        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->coverImage(),
            CompanyId::fromString($command->session()->company()),
        );
    }
}