<?php

namespace App\Sale\Payment\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreatePaymentCommand extends BaseCommand
{
    public function __construct(private string $order, private int $method, private array $payer)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('order'),
            $payload->int('method'),
            $payload->array('payer'),
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

    public function payer(): array
    {
        return $this->payer;
    } 
}