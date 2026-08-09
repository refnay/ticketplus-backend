<?php

namespace App\Sale\Shared\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class CompanyId extends UuidValueObject 
{
    #[Override]
    public function validate(): void
    {
    }
}
