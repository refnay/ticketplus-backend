<?php

namespace App\Catalog\Event\Domain;

class EventDay
{
    private EventDayId $id;
    private EventDayDate $date;
    private EventDayStartTime $startTime;
    private EventDayEndTime $endTime;
    private EventDayDescription $description;
    private EventDayStatus $status;
    private Event $event;

    public function __construct(
        EventDayId $id,
        EventDayDate $date,
        EventDayStartTime $startTime,
        EventDayEndTime $endTime,
        EventDayDescription $description,
        EventDayStatus $status,
        Event $event,
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->status = $status;
        $this->event = $event;
    }

    public static function create(
        EventDayDate $date,
        EventDayStartTime $startTime,
        EventDayEndTime $endTime,
        EventDayDescription $description,
        EventDayStatus $status,
        Event $event,
    ): self {
        return new self(
            EventDayId::generate(),
            $date,
            $startTime,
            $endTime,
            $description,
            $status,
            $event,
        );
    }

    public function id(): EventDayId
    {
        return $this->id;
    }

    public function date(): EventDayDate
    {
        return $this->date;
    }

    public function startTime(): EventDayStartTime
    {
        return $this->startTime;
    }

    public function endTime(): EventDayEndTime
    {
        return $this->endTime;
    }

    public function description(): EventDayDescription
    {
        return $this->description;
    }

    public function status(): EventDayStatus
    {
        return $this->status;
    }

    public function event(): Event
    {
        return $this->event;
    }

    public function changeDate(EventDayDate $date): void
    {
        $this->date = $date;
    }

    public function changeStartTime(EventDayStartTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function changeEndTime(EventDayEndTime $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function changeDescription(EventDayDescription $description): void
    {
        $this->description = $description;
    }

    public function changeStatus(EventDayStatus $status): void
    {
        $this->status = $status;
    }
}