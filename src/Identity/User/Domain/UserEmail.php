<?php

namespace App\Identity\User\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class UserEmail extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
