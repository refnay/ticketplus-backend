<?php

namespace App\Catalog\Event\Application\Choose;

class ChooseEventsQuery
{
    public static function create(): self
    {
        return new self();
    }
}
