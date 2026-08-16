<?php

namespace App\Sale\Payment\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreatePaymentCommand extends BaseCommand
{
    public function __construct(private string $order, private int $method)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('order'),
            $payload->int('method')
        );
    }

    public function order(): string
    {
        return $this->order;
    } 

    public function method(): int
    {
        return $this->method;
    } 
}