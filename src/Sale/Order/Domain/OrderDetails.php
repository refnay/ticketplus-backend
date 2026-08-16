<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class OrderDetails extends ArrayValueObject  
{
    #[Override]
    public function validate(): void
    {
    }

    public static function fromPattern(string $event, string $day, array $items): self
    {
        $elements = [];
        foreach ($items as $item) {
            $elements[] = self::itemPattern($item);
        }

        return new self([
            'event' => $event,
            'day' => $day,
            'items' => $elements,
        ]);
    }

    private static function itemPattern(array $item): array
    {
        return [
            'zone' => isset($item['zone']) ? (string) $item['zone'] : null,
            'quantity' => isset($item['quantity']) ? (int) $item['quantity'] : null,
            'seats' => isset($item['seats']) ? (array) $item['seats'] : null,
            'price' => isset($item['price']) ? (float) $item['price'] : null,
        ];
    }

    public function event(): ?string
    {
        return isset($this->value['event']) ? (string) $this->value['event'] : null;
    }

    public function day(): ?string
    {
        return isset($this->value['day']) ? (string) $this->value['day'] : null;
    }

    public function items(): array
    {
        return isset($this->value['items']) ? (array) $this->value['items'] : null;
    }
}
