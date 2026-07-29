<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class CompanyTelephone extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
