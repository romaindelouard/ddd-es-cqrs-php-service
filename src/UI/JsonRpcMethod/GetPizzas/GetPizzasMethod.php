<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas;

use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class GetPizzasMethod implements JsonRpcMethodInterface
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function apply(array $paramList = null): array
    {
        $query = new GetPizzasQuery();
        $result = $this->queryBus->ask($query);

        return $this->parseResult($result);
    }

    private function parseResult(Collection $collection): array
    {
        return [
            'meta' => [
                'size' => $collection->limit,
                'page' => $collection->page,
                'total' => $collection->total,
            ],
            'data' => $collection->data,
        ];
    }
}
