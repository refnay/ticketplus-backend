<?php

namespace App\Catalog\Event\Application\List;

class ListEventQueryHandler
{
    public function __construct(private EventLister $lister)
    {
    }

    public function __invoke(ListEventQuery $query): EventsResponse
    {
        return $this->lister->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}