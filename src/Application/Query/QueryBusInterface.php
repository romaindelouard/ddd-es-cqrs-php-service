<?php

namespace Romaind\PizzaStore\Application\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query);
}
