<?php

namespace Romaind\PizzaStore\Application\Query\Event\GetEvents;

use Assert\AssertionFailedException;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;

class GetEventsHandler implements QueryHandlerInterface
{
    private ElasticSearchEventRepository $eventRepository;

    public function __construct(ElasticSearchEventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @throws AssertionFailedException
     * @throws NotFoundException
     */
    public function __invoke(GetEventsQuery $query): Collection
    {
        $result = $this->eventRepository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, $result['total']['value'], $result['data']);
    }
}
