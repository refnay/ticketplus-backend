<?php

namespace App\Catalog\Event\Application\Find;

use App\Catalog\Event\Domain\EventDay;
use JsonSerializable;
use Override;

class EventDayResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $date,
        readonly private string $startTime,
        readonly private string $endTime,
        readonly private ?string $description,
        readonly private int $status,
    ) {
    }

    public static function create(EventDay $day): self
    {
        return new self(
            $day->id()->value(),
            $day->date()->asDMY(),
            $day->startTime()->asHM(),
            $day->endTime()->asHM(),
            $day->description()->value(),
            $day->status()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
