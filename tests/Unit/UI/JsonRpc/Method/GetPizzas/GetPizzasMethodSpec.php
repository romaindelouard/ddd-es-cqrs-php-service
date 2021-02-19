<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractQueryJsonRpcMethod;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasMethod;

class GetPizzasMethodSpec extends ObjectBehavior
{
    public function let(GetPizzasQuery $query, QueryBusInterface $queryBus)
    {
        $this->beConstructedWith($query);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasMethod::class);
        $this->shouldBeAnInstanceOf(AbstractQueryJsonRpcMethod::class);
    }
}
