<?php

namespace App\Identity\User\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class UserPassword extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
