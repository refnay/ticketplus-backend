<?php

namespace App\Catalog\Category\Application\Find;

use App\Catalog\Category\Domain\Category;
use JsonSerializable;
use Override;

class CategoryResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $name,
        readonly private int $reference,
    ) {
    }

    public static function create(Category $category): self
    {
        return new self(
            $category->id()->value(),
            $category->name()->value(),
            $category->reference()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
