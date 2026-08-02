<?php

namespace App\Shared\Domain\Events;

abstract class DomainEvent
{
    abstract public function payload(): array;
}
