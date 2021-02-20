<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\GetEvents;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Event\GetEvents\GetEventsQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractQueryJsonRpcMethod;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetEvents\GetEventsMethod;
use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;

class GetEventsMethodSpec extends ObjectBehavior
{
    public function let(
        GetEventsQuery $query,
        QueryBusInterface $queryBus,
        ParamsValidator $validator
    ) {
        $this->beConstructedWith($query);
        $this->setQueryBus($queryBus);
        $this->setValidator($validator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetEventsMethod::class);
        $this->shouldBeAnInstanceOf(AbstractQueryJsonRpcMethod::class);
    }

    public function it_should_get_pizzas(
        GetEventsQuery $query,
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
            ->validateParameters(Argument::cetera())
            ->shouldNotBeCalled();
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
