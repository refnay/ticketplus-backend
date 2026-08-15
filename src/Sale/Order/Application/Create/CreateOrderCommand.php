<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateOrderCommand extends BaseCommand
{
    public function __construct() {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self();
    }
}