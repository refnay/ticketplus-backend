<?php

namespace App\Sale\Purchase\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class CompanyId extends UuidValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
