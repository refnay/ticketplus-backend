<?php

namespace App\Shared\Application\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;

class EventBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object $message): mixed
    {
        return $this->messageBus->dispatch($message);
    }
}
