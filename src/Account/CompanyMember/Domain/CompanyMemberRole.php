<?php

namespace App\Account\CompanyMember\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class CompanyMemberRole extends IntValueObject
{
    public static function owner(): self
    {
        return new self(CompanyMemberRoleList::OWNER->value);
    }
    
    #[Override]
    public function validate(): void
    {
    }
}
