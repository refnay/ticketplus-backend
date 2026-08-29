<?php

namespace App\Account\Company\Domain;

use App\Shared\Domain\ValueObjects\ArrayValueObject;
use Override;

class CompanyDefault extends ArrayValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
