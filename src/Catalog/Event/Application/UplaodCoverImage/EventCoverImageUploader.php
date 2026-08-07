<?php

namespace App\Catalog\Event\Application\UplaodCoverImage;

use App\Catalog\Event\Domain\EventCoverImage;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventCoverImageNotUploaded;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Domain\Services\ImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        ?UploadedFile $coverImage,
        CompanyId $companyId
    ): void {
        $event = $this->finder->__invoke($id, $companyId);

        if (!is_null($coverImage)) {
            try {
                $url = $this->uploader->upload($coverImage->getRealPath());
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