<?php

namespace App\Account\User\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class UserStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(UserStatusList::PENDING->value);
    }
}
