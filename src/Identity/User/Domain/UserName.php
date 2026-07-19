<?php

namespace App\Identity\User\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class UserName extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
