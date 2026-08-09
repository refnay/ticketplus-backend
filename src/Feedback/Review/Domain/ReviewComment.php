<?php

namespace App\Feedback\Review\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class ReviewComment extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
