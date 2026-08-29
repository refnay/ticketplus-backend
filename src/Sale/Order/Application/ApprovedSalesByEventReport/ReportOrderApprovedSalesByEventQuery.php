<?php

namespace App\Sale\Order\Application\ApprovedSalesByEventReport;

use App\Shared\Application\Input\PayloadMapper;

class ReportOrderApprovedSalesByEventQuery
{
    public function __construct(
        private string $from,
        private string $to,
        private string $currency,
        private int $limit,
    ) {
    }

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('from'),
            $payload->string('to'),
            $payload->string('currency'),
            $payload->int('limit'),
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

    public function limit(): string
    {
        return $this->currency;
    }
}
