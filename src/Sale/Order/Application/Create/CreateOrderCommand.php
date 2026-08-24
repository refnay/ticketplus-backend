<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Support\ArrayBuilder;

class CreateOrderCommand
{
    public function __construct(
        private string $event,
        private string $day,
        private ?string $discount,
        private array $items,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $items = ArrayBuilder::generate();

        foreach ($payload->array('items') as $item) {
            $items->add(OrderItemCommand::create($item));
        }

        $items->removeDuplicates();

        return new self(
            $payload->string('event'),
            $payload->string('day'),
            $payload->nullableString('discount'),
            $items->items(),
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

    public function discount(): ?string
    {
        return $this->discount;
    }

    public function items(): array
    {
        return  $this->items;
    }
}
