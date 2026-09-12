<?php

namespace App\Sale\Order\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use DateInterval;
use Override;

class OrderExpiresAt extends DateTimeValueObject
{
    public static function start(): static
    {
        return self::now()->add(new DateInterval('PT15M'));
    }

    public function expired(): bool
    {
        return $this->before(self::now());
    }

    #[Override]
    public function validate(): void
    {
    }
}
