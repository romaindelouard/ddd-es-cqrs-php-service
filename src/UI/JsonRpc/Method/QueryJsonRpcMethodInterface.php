<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method;

use Romaind\PizzaStore\Application\Query\QueryBusInterface;

interface QueryJsonRpcMethodInterface
{
    public function setQueryBus(QueryBusInterface $queryBus): void;
}
