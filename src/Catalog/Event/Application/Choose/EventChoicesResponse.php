<?php

namespace App\Catalog\Event\Application\Choose;

use JsonSerializable;
use Override;

class EventChoicesResponse implements JsonSerializable
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
