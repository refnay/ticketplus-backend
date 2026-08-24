<?php

namespace App\Sale\Reference\User\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class UserId extends UuidValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
