<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class CompanyLogo extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
