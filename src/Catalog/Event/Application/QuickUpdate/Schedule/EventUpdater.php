<?php

namespace App\Catalog\Event\Application\QuickUpdate\Schedule;

use App\Catalog\Event\Application\Shared\EventDayCommand;
use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventDaySaleStartsAt;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventDayStatus;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    /** @param EventDayCommand[] $days */
    public function __invoke(EventId $id, CompanyId $companyId, array $days): void
    {
        $event = $this->eventFinder->__invoke($id, $companyId);
        $changes = [];
        foreach ($days as $command) {
            $day = $event->findDayById(EventDayId::fromString($command->id()));
            if ($day === null) {
                throw new EventDayNotFound();
            }

            $changes[] = [
                $day,
                EventDayDate::fromString($command->date()),
                EventDayStartTime::fromString($command->startTime()),
                EventDayEndTime::fromString($command->endTime()),
                EventDaySaleStartsAt::fromString($command->saleStartAt()),
                EventDayDescription::fromString($command->description()),
                EventDayStatus::fromInt($command->status()),
            ];
        }

        foreach ($changes as [$day, $date, $startTime, $endTime, $saleStartAt, $description, $status]) {
            $day->changeDate($date);
            $day->changeStartTime($startTime);
            $day->changeEndTime($endTime);
            $day->changeSaleStartAt($saleStartAt);
            $day->changeDescription($description);
            $day->changeStatus($status);
        }

        $this->repository->update($event);
    }
}
