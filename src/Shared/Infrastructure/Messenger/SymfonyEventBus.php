<?php

namespace App\Shared\Infrastructure\Messenger;

use App\Shared\Application\Bus\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(object ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
