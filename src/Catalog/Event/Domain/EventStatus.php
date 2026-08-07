<?php

namespace App\Catalog\Event\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class EventStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function draft(): self
    {
        return new self(EventStatusList::DRAFT->value);
    }
}
