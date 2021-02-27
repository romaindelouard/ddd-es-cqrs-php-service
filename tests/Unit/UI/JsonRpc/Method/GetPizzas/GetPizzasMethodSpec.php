<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractQueryJsonRpcMethod;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasMethod;
use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;

class GetPizzasMethodSpec extends ObjectBehavior
{
    public function let(
        GetPizzasQuery $query,
        QueryBusInterface $queryBus,
        ParamsValidator $validator
    ) {
        $this->beConstructedWith($query);
        $this->setQueryBus($queryBus);
        $this->setValidator($validator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasMethod::class);
        $this->shouldBeAnInstanceOf(AbstractQueryJsonRpcMethod::class);
    }

    public function it_should_get_pizzas(
        GetPizzasQuery $query,
        QueryBusInterface $queryBus,
        ParamsValidator $validator,
        Collection $collection
    ) {
        $parameters = ['page' => 3, 'limit' => 3];
        $collection->limit = 10;
        $collection->page = 3;
        $collection->total = 99;
        $collection->data = ['data'];

        $validator
            ->validateParameters(
                $parameters,
                Argument::type(GetPizzasConstraint::class)
            )
            ->shouldBeCalledTimes(1);
        $queryBus->ask($query)->willReturn($collection)->shouldBeCalledTimes(1);

        $this->apply($parameters)->shouldBe([
            'meta' => [
                'size' => 10,
                'page' => 3,
                'total' => 99,
            ],
            'data' => ['data'],
        ]);
    }
}
