<?php

namespace App\Catalog\Event\Application\DeleteDay;

class DeleteEventDayCommand
{
    public function __construct(private string $id, private string $dayId) {}

    public function id(): string
    {
        return $this->id;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }
}
