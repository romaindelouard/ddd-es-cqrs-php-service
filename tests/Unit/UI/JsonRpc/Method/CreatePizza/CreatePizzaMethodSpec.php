<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand as CreatePizzaCommand;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractCommandJsonRpcMethod;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaMethod;
use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;

class CreatePizzaMethodSpec extends ObjectBehavior
{
    public function let(
        PizzaRepositoryInterface $pizzaRepository,
        CommandBusInterface $commandBus,
        ParamsValidator $validator
    ) {
        $this->beConstructedWith($pizzaRepository);
        $this->setCommandBus($commandBus);
        $this->setValidator($validator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreatePizzaMethod::class);
        $this->shouldBeAnInstanceOf(AbstractCommandJsonRpcMethod::class);
    }

    public function it_should_create_a_pizza(
        PizzaRepositoryInterface $pizzaRepository,
        CommandBusInterface $commandBus,
        ParamsValidator $validator,
        Pizza $pizza,
        UuidInterface $pizzaUuid
    ) {
        $name = '4 cheeses';
        $description = 'mega cheese';
        $parameters = ['name' => $name, 'description' => $description];

        $validator
            ->validateParameters(
                $parameters,
                Argument::type(CreatePizzaConstraint::class)
            )
            ->shouldBeCalledTimes(1);
        $commandBus
            ->handle(Argument::allOf(
                Argument::type(CreatePizzaCommand::class),
                Argument::that(function ($command) use ($name, $description) {
                    if ($command->name !== $name) {
                        return false;
                    }
                    if ($command->description !== $description) {
                        return false;
                    }

                    return true;
                })
            ))
            ->shouldBeCalledTimes(1);

        $pizzaRepository
            ->get(Argument::any())
            ->willReturn($pizza)
            ->shouldBeCalledTimes(1);

        $pizza->getUuid()->willReturn($pizzaUuid)->shouldBeCalledTimes(1);
        $uuid = 'a8afbc39-8093-45dd-8c70-df59bdf113cf';
        $pizzaUuid->toString()->willReturn($uuid);
        $pizza->getName()->willReturn($name);
        $pizza->getDescription()->willReturn($description);

        $this->apply($parameters)->shouldReturn([
            'pizza' => [
                'id' => $uuid,
                'name' => $name,
                'description' => $description,
            ],
        ]);
    }
}
