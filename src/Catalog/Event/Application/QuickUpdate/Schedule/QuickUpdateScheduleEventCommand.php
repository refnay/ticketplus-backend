<?php

namespace App\Catalog\Event\Application\QuickUpdate\Schedule;

use App\Catalog\Event\Application\Shared\EventDayCommand;
use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Support\ArrayBuilder;

class QuickUpdateScheduleEventCommand
{
    public function __construct(private string $id, private array $days) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $days = ArrayBuilder::generate();

        foreach ($payload->array('days') as $day) {
            $days->add(EventDayCommand::create($day));
        }

        return new self($id, $days->items());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function days(): array
    {
        return $this->days;
    }
}
