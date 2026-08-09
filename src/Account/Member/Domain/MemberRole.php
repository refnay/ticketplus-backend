<?php

namespace App\Account\Member\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class MemberRole extends IntValueObject
{
    public static function owner(): self
    {
        return new self(MemberRoleList::OWNER->value);
    }
    
    #[Override]
    public function validate(): void
    {
    }
}
