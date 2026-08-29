<?php

namespace App\Catalog\Event\Application\UploadThumbnail;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventThumbnail;
use App\Catalog\Event\Domain\Exceptions\EventThumbnailNotUploaded;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Input\FileUpload;
use App\Shared\Application\Port\Image\ImageUploader;
use Throwable;

class EventThumbnailUploader
{
    public function __construct(
        private EventRepository $repository,
        private EventFinder $finder,
        private ImageUploader $uploader,
    ) {
    }

    public function __invoke(EventId $id, ?FileUpload $thumbnail, CompanyId $companyId): void
    {
        $event = $this->finder->__invoke($id, $companyId);

        if (!is_null($thumbnail)) {
            try {
                $event->changeThumbnail(EventThumbnail::fromString($this->uploader->upload($thumbnail->path())));
            } catch (Throwable) {
                throw new EventThumbnailNotUploaded();
            }
        } else {
            $event->changeThumbnail(EventThumbnail::fromNull());
        }

        $this->repository->update($event);
    }
}
