<?php

namespace App\Shared\Infrastructure\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessageHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $serviceId => $definition) {
            if (str_ends_with($serviceId, 'Handler')) {
                $definition->setPublic(true);
            }
        }
    }
}