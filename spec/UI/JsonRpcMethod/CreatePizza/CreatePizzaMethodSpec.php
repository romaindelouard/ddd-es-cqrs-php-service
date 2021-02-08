<?php

namespace spec\Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository\PizzaRepository;
use Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza\CreatePizzaMethod;

class CreatePizzaMethodSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus, PizzaRepository $pizzaRepository)
    {
        $this->beConstructedWith($commandBus, $pizzaRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreatePizzaMethod::class);
    }
}
