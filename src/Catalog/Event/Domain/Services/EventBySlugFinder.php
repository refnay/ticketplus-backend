<?php

namespace App\Catalog\Event\Domain\Services;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventSlug;
use App\Catalog\Event\Domain\Exceptions\EventNotFound;
use App\Catalog\Shared\Domain\CompanyId;

class EventBySlugFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventSlug $slug, CompanyId $companyId): Event
    {
        $event = $this->repository->findBySlug($slug, $companyId);

        if (is_null($event)) {
            throw new EventNotFound();
        }

        return $event;
    }
}