<?php

namespace App\Shared\Infrastructure\Dependency;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessageHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $serviceId => $definition) {
            if (str_ends_with($serviceId, 'CommandHandler')) {
                $definition->addTag('messenger.message_handler', ['bus' => 'command_bus']);
            } elseif (str_ends_with($serviceId, 'QueryHandler')) {
                $definition->addTag('messenger.message_handler', ['bus' => 'query_bus']);
            }
        }
    }
}
