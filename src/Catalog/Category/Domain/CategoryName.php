<?php

namespace App\Catalog\Category\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use Override;

class CategoryName extends StringValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
