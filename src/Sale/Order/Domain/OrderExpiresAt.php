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

    #[Override]
    public function validate(): void
    {
    }
}
