<?php

namespace App\Catalog\Event\Application\UplaodBannerImage;

use App\Catalog\Event\Domain\EventBannerImage;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventBannerImageNotUploaded;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Domain\Services\ImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class EventBannerImageUploader
{
    public function __construct(
        private EventRepository $repository,
        private EventFinder $finder,
        private ImageUploader $uploader
    ) {
    }

    public function __invoke(
        EventId $id,
        ?UploadedFile $bannerImage,
        CompanyId $companyId
    ): void {
        $event = $this->finder->__invoke($id, $companyId);

        if (!is_null($bannerImage)) {
            try {
                $url = $this->uploader->upload($bannerImage->getRealPath());
                $event->changeBannerImage(EventBannerImage::fromString($url));
            } catch (Throwable) {
                throw new EventBannerImageNotUploaded();
            }
        } else {
            $event->changeBannerImage(EventBannerImage::fromNull());
        }
        
        $this->repository->update($event);
    }
}