<?php

namespace App\Sale\Shared\Domain;

class EventDay
{
    private string $id;
    private string $date;
    private string $eventName;

    public function __construct(
        string $id,
        string $date,
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
        return new self($id, $date, $eventName);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }
}
