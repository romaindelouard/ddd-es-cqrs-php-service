<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Pizza\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryInterface;

class GetPizzasQuerySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasQuery::class);
        $this->shouldImplement(QueryInterface::class);
    }

    public function it_should_create_a_query()
    {
        $this->beConstructedWith(52, 3);
        $this->page->shouldBe(52);
        $this->limit->shouldBe(3);
    }
}
