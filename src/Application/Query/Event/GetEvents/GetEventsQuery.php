<?php

namespace Romaind\PizzaStore\Application\Query\Event\GetEvents;

use Romaind\PizzaStore\Application\Query\QueryInterface;

class GetEventsQuery implements QueryInterface
{
    public int $page;
    public int $limit;

    public function __construct(int $page = 1, int $limit = 50)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}
