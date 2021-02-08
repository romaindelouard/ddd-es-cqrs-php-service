<?php

namespace Romaind\PizzaStore\Infrastructure\Symfony5\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TagMethodsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('app.json_rpc_method') as $serviceId => $tags) {
            $container->findDefinition($serviceId)->addTag(
                'json_rpc_http_server.jsonrpc_method',
                [
                    'method' => lcfirst(substr($serviceId, strrpos($serviceId, '\\') + 1, -6)),
                ]
            );
        }
    }
}
