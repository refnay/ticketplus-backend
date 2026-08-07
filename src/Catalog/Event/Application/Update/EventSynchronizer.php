<?php

namespace App\Catalog\Event\Application\Update;

use App\Catalog\Event\Domain\Event;
use App\Catalog\Event\Domain\EventDay;
use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventDayStatus;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;

class EventSynchronizer
{
    public function days(Event $event, array $days): void
    {
        $processedDayIds = [];

        /** @var EventDayCommand $dayCommand */
        foreach ($days as $dayCommand) {
            $date = EventDayDate::fromString($dayCommand->date());
            $startTime = EventDayStartTime::fromString($dayCommand->startTime());
            $endTime = EventDayEndTime::fromString($dayCommand->endTime());
            $description = EventDayDescription::fromString($dayCommand->description());
            $status = EventDayStatus::fromInt($dayCommand->status());

            if (is_null($dayCommand->id())) {
                $day = EventDay::create(
                    $date,
                    $startTime,
                    $endTime,
                    $description,
                    $event,
                );

                $event->addDay($day);
            } else {
                $day = $event->findDayById(EventDayId::fromString($dayCommand->id()));

                if (is_null($day)) {
                    throw new EventDayNotFound();
                }

                $day->changeDate($date);
                $day->changeStartTime($startTime);
                $day->changeEndTime($endTime);
                $day->changeDescription($description);
                $day->changeStatus($status);
            }

            $processedDayIds[] = $day->id()->value();
        }

        foreach ($event->days() as $day) {
            if (!in_array($day->id()->value(), $processedDayIds, true)) {
                $event->removeDayById($day->id());
            }
        }
    }
}
