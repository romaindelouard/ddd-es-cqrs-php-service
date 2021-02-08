<?php

namespace spec\Romaind\PizzaStore\Application\Command\Pizza\Create;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateHandler;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;

class CreateHandlerSpec extends ObjectBehavior
{
    public function let(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->beConstructedWith($pizzaRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateHandler::class);
    }
}
