<?php

namespace App\Shared\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;
use Override;

class AuditUpdatedAt extends DateTimeValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
