<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class CompanyStatus extends IntValueObject
{
    public static function pending(): self
    {
        return new self(CompanyStatusList::PENDING->value);
    }
    
    #[Override]
    public function validate(): void
    {
    }
}
