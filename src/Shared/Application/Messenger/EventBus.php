<?php

namespace App\Shared\Application\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;

class EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object ...$messages): void
    {
        foreach ($messages as $message) {
            $this->messageBus->dispatch($message);
        }
    }
}
