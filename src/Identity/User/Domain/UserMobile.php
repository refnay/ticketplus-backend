<?php

namespace App\Identity\User\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class UserMobile extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
