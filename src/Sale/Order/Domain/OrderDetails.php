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

    public function fromPattern(string $event, string $day, string $zone): self
    {
        return new self([
            'event' => $event,
            'day' => $day,
            'zone' => $zone,
            'seats' => [],
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

    public function day(): ?string
    {
        return isset($this->value['day']) ? (string) $this->value['day'] : null;
    }
}
