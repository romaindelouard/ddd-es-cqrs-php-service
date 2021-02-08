<?php

namespace spec\Romaind\PizzaStore\Application\Query\Pizza\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasHandler;
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
    }
}
