<?php

namespace App\Catalog\Event\Application\UpdateDay;

use App\Catalog\Event\Application\Shared\EventDayCommand;

class UpdateEventDayCommand
{
    public function __construct(private string $id, private string $dayId, private EventDayCommand $day) {}

    public static function create(string $id, string $dayId, array $data): self
    {
        return new self($id, $dayId, EventDayCommand::create($data));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }

    public function day(): EventDayCommand
    {
        return $this->day;
    }
}
