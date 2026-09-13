<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Event\Domain\EventId;

class ViewPublishedEventQueryHandler
{
    public function __construct(private EventFinder $finder) {}

    public function __invoke(ViewPublishedEventQuery $query): EventResponse
    {
        return $this->finder->__invoke(EventId::fromString($query->id()));
    }
}
