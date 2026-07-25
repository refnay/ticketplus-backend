<?php

namespace App\Catalog\Category\Domain;

use App\Shared\Domain\ValueObjects\UuidValueObject;
use Override;

class CategoryId extends UuidValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
