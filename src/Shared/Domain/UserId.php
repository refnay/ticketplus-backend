<?php

namespace App\Shared\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class UserId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
