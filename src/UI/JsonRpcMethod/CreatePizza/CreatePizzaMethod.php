<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;

use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand as CreatePizza;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository\PizzaRepository;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class CreatePizzaMethod implements JsonRpcMethodInterface
{
    private CommandBusInterface $commandBus;
    private PizzaRepository $pizzaRepository;

    public function __construct(CommandBusInterface $commandBus, PizzaRepository $pizzaRepository)
    {
        $this->commandBus = $commandBus;
        $this->pizzaRepository = $pizzaRepository;
    }

    public function apply(array $paramList = null): array
    {
        $pizzaId = Uuid::uuid4();
        $command = new CreatePizza(
            $pizzaId,
            $paramList['name'],
            $paramList['description']
        );

        $this->commandBus->handle($command);
        $pizza = $this->pizzaRepository->get($pizzaId);

        return [
            'pizza' => [
                'id' => $pizza->getUuid()->toString(),
                'name' => $pizza->getName(),
                'description' => $pizza->getDescription(),
            ],
        ];
    }
}
