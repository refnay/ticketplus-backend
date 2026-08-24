<?php

namespace App\Catalog\Event\Application\UploadCoverImage;

use App\Catalog\Event\Domain\EventCoverImage;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventCoverImageNotUploaded;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Port\Image\ImageUploader;
use App\Shared\Application\Input\FileUpload;
use Throwable;

class EventCoverImageUploader
{
    public function __construct(
        private EventRepository $repository,
        private EventFinder $finder,
        private ImageUploader $uploader
    ) {
    }

    public function __invoke(
        EventId $id,
        ?FileUpload $coverImage,
        CompanyId $companyId
    ): void {
        $event = $this->finder->__invoke($id, $companyId);

        if (!is_null($coverImage)) {
            try {
                $url = $this->uploader->upload($coverImage->path());
                $event->changeCoverImage(EventCoverImage::fromString($url));
            } catch (Throwable) {
                throw new EventCoverImageNotUploaded();
            }
        } else {
            $event->changeCoverImage(EventCoverImage::fromNull());
        }
        
        $this->repository->update($event);
    }
}
