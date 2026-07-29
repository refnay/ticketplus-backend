<?php

namespace App\Catalog\Zone\Application\Delete;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class DeleteZoneCommandHandler
{
    public function __construct(private ZoneDeleter $deleter)
    {
    }

    public function __invoke(DeleteZoneCommand $command): void
    {
        $this->deleter->__invoke(
            ZoneId::fromString($command->id()),
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}