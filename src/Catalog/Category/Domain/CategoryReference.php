<?php

namespace App\Catalog\Category\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class CategoryReference extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }
}
