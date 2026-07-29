<?php

namespace App\Catalog\Seat\Application\Create;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class CreateSeatCommandHandler
{
    public function __construct(private SeatCreator $creator)
    {
    }

    public function __invoke(CreateSeatCommand $command): string
    {
        return $this->creator->__invoke(
            SeatCode::fromString($command->code()),
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}