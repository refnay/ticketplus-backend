<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\DateValueObject;
use Override;

class UserBirthDate extends DateValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
