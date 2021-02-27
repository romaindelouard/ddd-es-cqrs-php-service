<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Query\Event\GetEvents;

use Assert\AssertionFailedException;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Event\SearchEventRepositoryInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;

class GetEventsHandler implements QueryHandlerInterface
{
    private SearchEventRepositoryInterface $searchEventRepository;

    public function __construct(SearchEventRepositoryInterface $searchEventRepository)
    {
        $this->searchEventRepository = $searchEventRepository;
    }

    /**
     * @throws AssertionFailedException
     * @throws NotFoundException
     */
    public function __invoke(GetEventsQuery $query): Collection
    {
        $result = $this->searchEventRepository->page($query->page, $query->limit);

        return new Collection($query->page, $query->limit, $result['total']['value'], $result['data']);
    }
}
