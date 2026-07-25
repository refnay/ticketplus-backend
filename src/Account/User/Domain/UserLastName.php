<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class UserLastName extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
