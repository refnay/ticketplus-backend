<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class UserProfileImage extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
