<?php

namespace Romaind\PizzaStore\Application\Query;

interface QueryBusInterface
{
    /**
     * @return mixed
     */
    public function ask(QueryInterface $query);
}
