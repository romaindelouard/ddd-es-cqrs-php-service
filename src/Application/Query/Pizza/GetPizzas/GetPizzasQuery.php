<?php

namespace Romaind\PizzaStore\Application\Query\Pizza\GetPizzas;

use Romaind\PizzaStore\Application\Query\QueryInterface;

class GetPizzasQuery implements QueryInterface
{
    public int $page;
    public int $limit;

    public function __construct(int $page = 1, int $limit = 50)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}
