<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;

use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand as CreatePizza;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;
use Romaind\PizzaStore\UI\JsonRpcMethod\AbstractCommandJsonRpcMethod;

class CreatePizzaMethod extends AbstractCommandJsonRpcMethod
{
    private PizzaRepositoryInterface $pizzaRepository;

    public function __construct(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
    }

    public function apply(array $parameters = null): array
    {
        $pizzaId = Uuid::uuid4();
        $this->command = new CreatePizza(
            $pizzaId,
            $parameters['name'],
            $parameters['description']
        );

        parent::apply($parameters);

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
