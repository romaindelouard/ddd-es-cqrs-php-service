<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Event\GetEvents;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Event\GetEvents\GetEventsQuery;
use Romaind\PizzaStore\Application\Query\QueryInterface;

class GetEventsQuerySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(GetEventsQuery::class);
        $this->shouldImplement(QueryInterface::class);
    }

    public function it_should_create_a_query()
    {
        $this->beConstructedWith(52, 3);
        $this->page->shouldBe(52);
        $this->limit->shouldBe(3);
    }
}
