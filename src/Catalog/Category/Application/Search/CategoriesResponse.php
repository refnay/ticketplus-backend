<?php

namespace App\Catalog\Category\Application\Search;

use JsonSerializable;
use Override;

class CategoriesResponse implements JsonSerializable 
{
    private array $categories = [];

    public function __construct(private int $total, CategoryResponse ...$categories)
    {
        $this->categories = $categories;
    }

    public function categories(): array
    {
        return $this->categories;
    }

    public function total(): int
    {
        return $this->total;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this); 
    }
}