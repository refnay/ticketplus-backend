<?php

namespace App\Sale\Payment\Application\Confirm;

use App\Shared\Application\Input\PayloadMapper;

class UpdatePaymentCommand
{
    public function __construct(private string $payment, private string $token)
    {
    }

    public static function create(string $payment, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payment,
            $payload->string('token'),
        );
    }

    public function payment(): string
    {
        return $this->payment;
    }

    public function token(): string
    {
        return $this->token;
    }
}
