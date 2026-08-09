<?php

namespace App\Feedback\Review\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class ReviewRating extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
