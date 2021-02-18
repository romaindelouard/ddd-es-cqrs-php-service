<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\GetEvents;

use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Event\GetEvents\GetEventsQuery;
use Romaind\PizzaStore\UI\JsonRpcMethod\AbstractQueryJsonRpcMethod;

class GetEventsMethod extends AbstractQueryJsonRpcMethod
{
    public function __construct(GetEventsQuery $query)
    {
        $this->query = $query;
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
