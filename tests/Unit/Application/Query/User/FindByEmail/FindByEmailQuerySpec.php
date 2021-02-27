<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\User\FindByEmail;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Application\Query\User\FindByEmail\FindByEmailQuery;

class FindByEmailQuerySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('toto@mail.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FindByEmailQuery::class);
        $this->shouldImplement(QueryInterface::class);
    }
}
