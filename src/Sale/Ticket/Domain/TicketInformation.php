<?php

namespace App\Sale\Ticket\Domain;

use DateTimeInterface;

class TicketInformation
{
    public function __construct(
        private DateTimeInterface $date,
        private string $eventName,
        private string $zoneName,
        private ?string $seatCode,
    ) {
    }

    public static function create(
        DateTimeInterface $date,
        string $eventName,
        string $zoneName,
        ?string $seatCode
    ): self {
        return new self($date, $eventName, $zoneName, $seatCode);
    }

    public static function fromData(array $data): self
    {
        return new self($data['date'], $data['eventName'], $data['zoneName'], $data['seatCode']);
    }

    public function date(): DateTimeInterface 
    {
        return $this->date;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public function zoneName(): string
    {
        return $this->zoneName;
    }

    public function seatCode(): ?string
    {
        return $this->seatCode;
    }

    public function toArray(): array
    {
        return [
            'dayDate' => $this->date->format('d/m/Y'),
            'eventName' => $this->eventName,
            'zoneName' => $this->zoneName,
            'seatCode' => $this->seatCode,
        ];
    }
}
