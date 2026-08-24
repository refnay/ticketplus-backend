<?php

namespace App\Shared\Application\Bus;

interface EventBus
{
    public function publish(object ...$events): void;
}
