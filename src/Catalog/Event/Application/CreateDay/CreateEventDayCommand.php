<?php

namespace App\Catalog\Event\Application\CreateDay;

use App\Catalog\Event\Application\Shared\EventDayCommand;

class CreateEventDayCommand
{
    public function __construct(private string $id, private EventDayCommand $day) {}

    public static function create(string $id, array $data): self
    {
        return new self($id, EventDayCommand::create($data));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function day(): EventDayCommand
    {
        return $this->day;
    }
}
