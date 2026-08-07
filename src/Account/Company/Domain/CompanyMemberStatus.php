<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class CompanyMemberStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function active(): self
    {
        return new self(CompanyMemberStatusList::ACTIVE->value);
    } 
}
