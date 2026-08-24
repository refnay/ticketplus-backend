<?php

namespace App\Sale\Payment\Application\Update;

use App\Shared\Application\Input\PayloadMapper;

class UpdatePaymentCommand
{
    public function __construct(private string $payment, private string $order, private string $token)
    {
    }

    public static function create(string $payment, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payment,
            $payload->string('order'),
            $payload->string('token'),
        );
    }

    public function payment(): string
    {
        return $this->payment;
    } 

    public function order(): string
    {
        return $this->order;
    }

    public function token(): string
    {
        return $this->token;
    } 
}