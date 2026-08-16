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

    public static function fromPattern(string $event, string $day, string $zone, int $quantity, ?array $seats): self
    {
        return new self([
            'event' => $event,
            'day' => $day,
            'zone' => $zone,
            'quantity' => $quantity,
            'seats' => $seats,
        ]);
    }

    public function event(): ?string
    {
        return isset($this->value['event']) ? (string) $this->value['event'] : null;
    }

    public function zone(): ?string
    {
        return isset($this->value['zone']) ? (string) $this->value['zone'] : null;
    }

    public function quantity(): ?int
    {
        return isset($this->value['quantity']) ? (int) $this->value['quantity'] : null;
    }

    public function day(): ?string
    {
        return isset($this->value['day']) ? (string) $this->value['day'] : null;
    }

    public function seats(): ?array
    {
        return isset($this->value['seats']) && is_array($this->value['seats']) && count($this->value['seats']) > 0
            ? (array) $this->value['seats']
            : null;
    }
}
