<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class CompanyMemberId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
