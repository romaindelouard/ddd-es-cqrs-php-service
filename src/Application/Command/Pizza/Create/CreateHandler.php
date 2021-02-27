<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command\Pizza\Create;

use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;

class CreateHandler implements CommandHandlerInterface
{
    private PizzaRepositoryInterface $pizzaRepository;

    public function __construct(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
    }

    public function __invoke(CreateCommand $command): void
    {
        $pizza = Pizza::create(
            Uuid::fromString($command->uuid),
            $command->name,
            $command->description
        );

        $this->pizzaRepository->store($pizza);
    }
}
