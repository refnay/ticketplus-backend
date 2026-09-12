<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Event\Domain\EventId;

class ViewPublishedEventQueryHandler
{
    public function __construct(private PublishedEventViewer $viewer)
    {
    }

    public function __invoke(ViewPublishedEventQuery $query): PublishedEventResponse
    {
        return $this->viewer->__invoke(EventId::fromString($query->id()));
    }
}
