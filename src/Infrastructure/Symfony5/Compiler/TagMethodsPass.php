<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Symfony5\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TagMethodsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $services = $container->findTaggedServiceIds('app.json_rpc_method');
        foreach ($services as $serviceId => $tags) {
            $container->findDefinition($serviceId)->addTag(
                'json_rpc_http_server.jsonrpc_method',
                [
                    'method' => lcfirst(substr($serviceId, strrpos($serviceId, '\\') + 1, -6)),
                ]
            );
        }
    }
}
