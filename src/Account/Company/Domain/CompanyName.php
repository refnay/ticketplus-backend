<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class CompanyName extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
