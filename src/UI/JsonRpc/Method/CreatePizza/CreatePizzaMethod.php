<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\Pizza\Create\CreateCommand as CreatePizza;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractCommandJsonRpcMethod;

class CreatePizzaMethod extends AbstractCommandJsonRpcMethod
{
    private PizzaRepositoryInterface $pizzaRepository;

    public function __construct(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
        $this->constraint = new CreatePizzaConstraint();
    }

    public function apply(array $parameters = null): array
    {
        $pizzaUuid = Uuid::uuid4();
        $this->command = new CreatePizza(
            $pizzaUuid->toString(),
            $parameters['name'],
            $parameters['description']
        );

        parent::apply($parameters);

        $pizza = $this->pizzaRepository->get($pizzaUuid);

        return [
            'pizza' => [
                'id' => $pizza->getUuid()->toString(),
                'name' => $pizza->getName(),
                'description' => $pizza->getDescription(),
            ],
        ];
    }
}
