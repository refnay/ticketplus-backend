<?php

namespace App\Account\CompanyMember\Domain;

use App\Shared\Domain\ValueObjects\BooleanValueObject;
use Override;

class CompanyMemberCurrent extends BooleanValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
