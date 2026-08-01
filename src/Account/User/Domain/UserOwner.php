<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\BooleanValueObject;
use Override;

class UserOwner extends BooleanValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
