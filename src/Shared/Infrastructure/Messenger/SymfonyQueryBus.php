<?php

namespace App\Shared\Infrastructure\Messenger;

use App\Shared\Application\Bus\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    public function ask(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \LogicException(sprintf('No query handler processed %s.', $query::class));
        }

        return $stamp->getResult();
    }
}
