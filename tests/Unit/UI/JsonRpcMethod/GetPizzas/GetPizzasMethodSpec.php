<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas\GetPizzasMethod;

class GetPizzasMethodSpec extends ObjectBehavior
{
    public function let(QueryBusInterface $queryBus)
    {
        $this->beConstructedWith($queryBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasMethod::class);
    }
}
