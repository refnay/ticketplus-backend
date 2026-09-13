<?php

namespace App\Catalog\Event\Application\UpdateDay;

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

class EventDayUpdater
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(
        EventId $id,
        EventDayId $dayId,
        CompanyId $companyId,
        EventDayDate $date,
        EventDayStartTime $startTime,
        EventDayEndTime $endTime,
        EventDaySaleStartsAt $saleStartAt,
        EventDayDescription $description,
        EventDayStatus $status,
    ): void {
        $event = $this->eventFinder->__invoke($id, $companyId);
        $day = $event->findDayById($dayId);
        if ($day === null) {
            throw new EventDayNotFound();
        }

        $day->changeDate($date);
        $day->changeStartTime($startTime);
        $day->changeEndTime($endTime);
        $day->changeSaleStartAt($saleStartAt);
        $day->changeDescription($description);
        $day->changeStatus($status);

        $this->repository->update($event);
    }
}
