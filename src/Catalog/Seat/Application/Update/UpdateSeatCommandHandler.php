<?php

namespace App\Catalog\Seat\Application\Update;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class UpdateSeatCommandHandler
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(UpdateSeatCommand $command): void
    {
        $this->updater->__invoke(
            SeatId::fromString($command->id()),
            SeatCode::fromString($command->code()),
            SeatStatus::fromInt($command->status()),
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            ZoneId::fromString($command->zone()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}