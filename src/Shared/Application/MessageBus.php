<?php

namespace App\Shared\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MessageBus
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function ask(object $query): mixed
    {
        return $this->handle($query);
    }

    public function dispatch(object $command): mixed
    {
        return $this->handle($command);
    }

    private function handle(object $message): mixed
    {
        $handlerClass = $message::class . 'Handler';

        if (!$this->container->has($handlerClass)) {
            throw new \LogicException(sprintf('Handler service not found for %s: %s.', $message::class, $handlerClass));
        }

        $handler = $this->container->get($handlerClass);

        if (!is_callable($handler)) {
            throw new \LogicException(sprintf('Handler %s must be invokable.', $handlerClass));
        }

        return $handler($message);
    }
}