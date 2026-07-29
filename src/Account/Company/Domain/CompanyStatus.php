<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class CompanyStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
