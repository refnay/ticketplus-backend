<?php

namespace App\Catalog\Event\Application\UplaodBannerImage;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class UploadEventBannerImageCommandHandler
{
    public function __construct(private EventBannerImageUploader $uploader)
    {
    }

    public function __invoke(UploadEventBannerImageCommand $command): void
    {
        $this->uploader->__invoke(
            EventId::fromString($command->id()),
            $command->bannerImage(),
            CompanyId::fromString($command->session()->company()),
        );
    }
}