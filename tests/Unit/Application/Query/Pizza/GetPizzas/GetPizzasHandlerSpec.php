<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Pizza\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasHandler;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;

class GetPizzasHandlerSpec extends ObjectBehavior
{
    public function let(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->beConstructedWith($pizzaRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    public function it_should_handle(
        PizzaRepositoryInterface $pizzaRepository,
        GetPizzasQuery $query
    ) {
        $page = 1;
        $limit = 23;
        $query->page = $page;
        $query->limit = $limit;

        $result = [['name' => 'pizza']];
        $pizzaRepository->page($page, $query->limit)
            ->willReturn($result)->shouldBeCalledTimes(1);

        $collection = $this->__invoke($query);

        $collection->page->shouldReturn($page);
        $collection->limit->shouldReturn($limit);
        $collection->total->shouldReturn(count($result));
        $collection->data->shouldReturn($result);
    }
}
