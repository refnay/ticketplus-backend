<?php

namespace App\Catalog\Category\Application\Choose;

use JsonSerializable;
use Override;

class CategoryChoicesResponse implements JsonSerializable
{
    private array $choices;

    public function __construct(array ...$choices)
    {
        $this->choices = $choices;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return $this->choices;
    }
}
