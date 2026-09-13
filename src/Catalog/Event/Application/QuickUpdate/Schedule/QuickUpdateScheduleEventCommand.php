<?php

namespace App\Catalog\Event\Application\QuickUpdate\Schedule;

use App\Catalog\Event\Application\Shared\EventDayCommand;
use App\Shared\Application\Input\PayloadMapper;

class QuickUpdateScheduleEventCommand
{
    /** @param EventDayCommand[] $days */
    public function __construct(private string $id, private array $days) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $days = [];
        foreach ($payload->array('days') as $day) {
            $days[] = EventDayCommand::create($day);
        }

        return new self($id, $days);
    }

    public function id(): string
    {
        return $this->id;
    }

    /** @return EventDayCommand[] */
    public function days(): array
    {
        return $this->days;
    }
}
