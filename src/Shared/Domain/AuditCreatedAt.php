<?php

namespace App\Shared\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class AuditCreatedAt extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
