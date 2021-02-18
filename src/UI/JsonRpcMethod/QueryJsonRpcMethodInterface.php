<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Romaind\PizzaStore\Application\Query\QueryBusInterface;

interface QueryJsonRpcMethodInterface
{
    public function setQueryBus(QueryBusInterface $queryBus): void;
}
