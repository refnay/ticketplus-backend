<?php

namespace App\Catalog\Event\Domain\Services;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventNotFound;

class PublishedEventFinder
{
    public function __construct(private EventRepository $repository)
    {
    }

    public function __invoke(EventId $id): Event
    {
        $event = $this->repository->findPublishedById($id);

        if (is_null($event)) {
            throw new EventNotFound();
        }

        return $event;
    }
}
