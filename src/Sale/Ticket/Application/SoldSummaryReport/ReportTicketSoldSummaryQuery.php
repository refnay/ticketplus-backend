<?php

namespace App\Sale\Ticket\Application\SoldSummaryReport;

use App\Shared\Application\Input\PayloadMapper;

class ReportTicketSoldSummaryQuery
{
    public function __construct(private string $from, private string $to)
    {
    }

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('from'),
            $payload->string('to'),
        );
    }

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
    }
}
