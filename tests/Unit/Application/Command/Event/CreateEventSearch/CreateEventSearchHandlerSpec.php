<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Command\Event\CreateEventSearch;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Application\Command\Event\CreateEventSearch\CreateEventSearchCommand;
use Romaind\PizzaStore\Application\Command\Event\CreateEventSearch\CreateEventSearchHandler;
use Romaind\PizzaStore\Domain\Event\SearchEventRepositoryInterface;

class CreateEventSearchHandlerSpec extends ObjectBehavior
{
    public function let(SearchEventRepositoryInterface $searchEventRepository)
    {
        $this->beConstructedWith($searchEventRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateEventSearchHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    public function it_should_create_search_event(
        CreateEventSearchCommand $command,
        SearchEventRepositoryInterface $searchEventRepository
    ) {
        $searchEventRepository->boot()->shouldBeCalledTimes(1);

        $this->__invoke($command);
    }
}
