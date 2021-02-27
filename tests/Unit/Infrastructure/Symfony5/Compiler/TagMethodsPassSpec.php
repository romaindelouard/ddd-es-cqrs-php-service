<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Symfony5\Compiler;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Symfony5\Compiler\TagMethodsPass;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaMethod;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasMethod;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TagMethodsPassSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagMethodsPass::class);
        $this->shouldImplement(CompilerPassInterface::class);
    }

    public function it_should_process_tag_methods(
        ContainerBuilder $container,
        Definition $definition1,
        Definition $definition2
    ) {
        $container
            ->findTaggedServiceIds('app.json_rpc_method')
            ->willReturn([
                CreatePizzaMethod::class => [[]],
                GetPizzasMethod::class => [[]],
            ])
            ->shouldBeCalledTimes(1);

        $container
            ->findDefinition(CreatePizzaMethod::class)
            ->willReturn($definition1)
            ->shouldBeCalledTimes(1);
        $definition1
            ->addTag(
                'json_rpc_http_server.jsonrpc_method',
                ['method' => 'createPizza']
            )
            ->shouldBeCalledTimes(1);

        $container
            ->findDefinition(GetPizzasMethod::class)
            ->willReturn($definition2)
            ->shouldBeCalledTimes(1);
        $definition2
            ->addTag(
                'json_rpc_http_server.jsonrpc_method',
                ['method' => 'getPizzas']
            )
            ->shouldBeCalledTimes(1);

        $this->process($container);
    }
}
