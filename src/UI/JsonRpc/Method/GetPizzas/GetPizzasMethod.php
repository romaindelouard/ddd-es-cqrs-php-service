<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\UI\JsonRpc\Method\AbstractQueryJsonRpcMethod;

class GetPizzasMethod extends AbstractQueryJsonRpcMethod
{
    public function __construct(GetPizzasQuery $query)
    {
        $this->query = $query;
        $this->constraint = new GetPizzasConstraint();
    }

    protected function parseResult(Collection $collection): array
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
