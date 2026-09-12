<?php

namespace App\Account\Member\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class MemberStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function active(): self
    {
        return new self(MemberStatusList::ACTIVE->value);
    }
}
