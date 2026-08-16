<?php

namespace App\Sale\Order\Application\Expire;

use App\Shared\Application\Command\BaseCommand;

class ExpireOrderCommand extends BaseCommand
{
    public function __construct(private string $order)
    {
    }

    public static function create(string $order): self
    {
        return new self($order);
    }

    public function order(): string
    {
        return $this->order;
    } 
}