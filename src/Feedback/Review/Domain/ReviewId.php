<?php

namespace App\Feedback\Review\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class ReviewId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
