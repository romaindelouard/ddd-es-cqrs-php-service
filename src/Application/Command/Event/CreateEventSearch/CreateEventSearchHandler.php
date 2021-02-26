<?php

namespace Romaind\PizzaStore\Application\Command\Event\CreateEventSearch;

use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Domain\Event\SearchEventRepositoryInterface;

class CreateEventSearchHandler implements CommandHandlerInterface
{
    private SearchEventRepositoryInterface $searchEventRepository;

    public function __construct(SearchEventRepositoryInterface $searchEventRepository)
    {
        $this->searchEventRepository = $searchEventRepository;
    }

    public function __invoke(CreateEventSearchCommand $command): void
    {
        $this->searchEventRepository->boot();
    }
}
