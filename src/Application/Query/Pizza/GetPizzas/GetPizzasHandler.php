<?php

namespace Romaind\PizzaStore\Application\Query\Pizza\GetPizzas;

use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;

class GetPizzasHandler implements QueryHandlerInterface
{
    private PizzaRepositoryInterface $pizzaRepository;

    public function __construct(PizzaRepositoryInterface $pizzaRepository)
    {
        $this->pizzaRepository = $pizzaRepository;
    }

    public function __invoke(GetPizzasQuery $query): Collection
    {
        $result = $this->pizzaRepository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, count($result), $result);
    }
}
