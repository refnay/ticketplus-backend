<?php

namespace App\Sale\Ticket\Application\ReportValidationSummary;

use App\Shared\Application\Input\PayloadMapper;

class ReportTicketValidationSummaryQuery
{
    public function __construct(private string $event, private string $day) {}

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('event'),
            $payload->string('day'),
        );
    }

    public function event(): string
    {
        return $this->event;
    }

    public function day(): string
    {
        return $this->day;
    }
}
