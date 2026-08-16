<?php

namespace App\Sale\Shared\Domain;

use DateTimeImmutable;

class EventDay
{
    private string $id;
    private DateTimeImmutable $date;
    private string $eventName;

    public function __construct(
        string $id,
        DateTimeImmutable $date,
        string $eventName,
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->eventName = $eventName;
    }

    public static function create(
        string $id,
        string $date,
        string $eventName,
    ): self {
        return new self($id, DateTimeImmutable::createFromFormat('Y-m-d', $date), $eventName);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }
}
