<?php

namespace App\Account\Member\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class MemberId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
