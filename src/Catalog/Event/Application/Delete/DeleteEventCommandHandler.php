<?php

namespace App\Catalog\Event\Application\Delete;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class DeleteEventCommandHandler
{
    public function __construct(private EventDeleter $deleter)
    {
    }

    public function __invoke(DeleteEventCommand $query): void
    {
        $this->deleter->__invoke(
            EventId::fromString($query->id()),
            CompanyId::fromString($query->session()->company()),
        );
    }
}