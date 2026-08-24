<?php

namespace App\Shared\Infrastructure\Messenger;

use App\Shared\Application\Bus\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(object $command): mixed
    {
        $envelope = $this->commandBus->dispatch($command);
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \LogicException(sprintf('No command handler processed %s.', $command::class));
        }

        return $stamp->getResult();
    }
}
