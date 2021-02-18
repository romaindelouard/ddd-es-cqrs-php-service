<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Query;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;

class CollectionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(1, 10, 100, []);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Collection::class);
    }

    public function it_should_throw_a_not_found_exception()
    {
        $this->beConstructedWith(11, 10, 100, []);

        $this->shouldThrow(NotFoundException::class)->duringInstantiation();
    }
}
