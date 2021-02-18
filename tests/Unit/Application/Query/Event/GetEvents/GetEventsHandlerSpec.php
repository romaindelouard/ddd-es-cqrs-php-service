<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Event\GetEvents;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Event\GetEvents\GetEventsHandler;
use Romaind\PizzaStore\Application\Query\Event\GetEvents\GetEventsQuery;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;

class GetEventsHandlerSpec extends ObjectBehavior
{
    public function let(ElasticSearchEventRepository $eventRepository)
    {
        $this->beConstructedWith($eventRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetEventsHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    public function it_should_handle(
        ElasticSearchEventRepository $eventRepository,
        GetEventsQuery $query
    ) {
        $page = 1;
        $limit = 23;
        $query->page = $page;
        $query->limit = $limit;

        $result = ['total' => ['value' => 123], 'data' => ['name' => 'pizza']];
        $eventRepository->page($page, $query->limit)
            ->willReturn($result)->shouldBeCalledTimes(1);

        $collection = $this->__invoke($query);

        $collection->page->shouldReturn($page);
        $collection->limit->shouldReturn($limit);
        $collection->total->shouldReturn(123);
        $collection->data->shouldReturn(['name' => 'pizza']);
    }
}
