<?php

namespace App\Sale\EventDay\Domain;

use DateTimeImmutable;

class EventDay
{
    public function __construct(
        private string $id,
        private DateTimeImmutable $date,
        private string $eventId,
    ) {
    }

    public static function create(
        string $id,
        string $date,
        string $eventId,
    ): self {
        return new self($id, DateTimeImmutable::createFromFormat('Y-m-d', $date), $eventId);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }
}
