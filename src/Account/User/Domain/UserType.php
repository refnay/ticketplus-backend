<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class UserType extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function simple(): self
    {
        return new self(UserTypesList::SIMPLE->value);
    }

    public static function worker(): self
    {
        return new self(UserTypesList::WORKER->value);
    }
}
