<?php

namespace App\Sale\Order\Application\PaidCountSummaryReport;

use App\Shared\Application\Input\PayloadMapper;

class ReportOrderPaidCountSummaryQuery
{
    public function __construct(private string $from, private string $to, private string $currency)
    {
    }

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('from'),
            $payload->string('to'),
            $payload->string('currency'),
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

    public function currency(): string
    {
        return $this->currency;
    }
}
