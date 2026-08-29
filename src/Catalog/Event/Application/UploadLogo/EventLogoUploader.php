<?php

namespace App\Catalog\Event\Application\UploadLogo;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLogo;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventLogoNotUploaded;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Input\FileUpload;
use App\Shared\Application\Port\Image\ImageUploader;
use Throwable;

class EventLogoUploader
{
    public function __construct(
        private EventRepository $repository,
        private EventFinder $finder,
        private ImageUploader $uploader,
    ) {
    }

    public function __invoke(EventId $id, ?FileUpload $logo, CompanyId $companyId): void
    {
        $event = $this->finder->__invoke($id, $companyId);

        if (!is_null($logo)) {
            try {
                $event->changeLogo(EventLogo::fromString($this->uploader->upload($logo->path())));
            } catch (Throwable) {
                throw new EventLogoNotUploaded();
            }
        } else {
            $event->changeLogo(EventLogo::fromNull());
        }

        $this->repository->update($event);
    }
}
