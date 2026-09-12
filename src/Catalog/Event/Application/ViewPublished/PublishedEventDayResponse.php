<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Catalog\Event\Domain\EventDay;
use JsonSerializable;
use Override;

class PublishedEventDayResponse implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $date,
        private string $startTime,
        private string $endTime,
        private string $saleStartsAt,
        private ?string $description,
        private int $status,
    ) {
    }

    public static function create(EventDay $day): self
    {
        return new self(
            $day->id()->value(),
            $day->date()->format(),
            $day->startTime()->asHM(),
            $day->endTime()->asHM(),
            $day->saleStartAt()->asDMYHM(),
            $day->description()->value(),
            $day->status()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
