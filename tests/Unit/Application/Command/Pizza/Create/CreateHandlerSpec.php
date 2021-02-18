<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Command\Pizza\Create;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateHandler;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
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

    public function it_should_invoke(
        PizzaRepositoryInterface $pizzaRepository,
        CreateCommand $command
    ) {
        $uuid = 'd070f6a3-6ae4-4660-bdf0-d2064b565d31';
        $name = '4 cheeses';
        $descrition = 'it is a wonderfull pizza';

        $command->uuid = $uuid; 
        $command->name = $name;
        $command->description = $descrition;

        $pizzaRepository
            ->store(Argument::allOf(
                Argument::type(Pizza::class),
                Argument::that(function ($pizza) use ($uuid, $descrition, $name) {
                    if ($pizza->getUuid() !== $uuid) {
                        return true;
                    }
                    if ($pizza->getName() !== $name) {
                        return true;
                    }
                    if ($pizza->getDescription() !== $description) {
                        return true;
                    }

                    return false;
                })
            ))
            ->shouldBeCalledTimes(1);

        $this->__invoke($command);
    }
}
