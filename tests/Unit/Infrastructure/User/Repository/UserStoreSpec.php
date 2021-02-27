<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Repository;

use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\AggregateFactory;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Infrastructure\User\Repository\UserStore;

class UserStoreSpec extends ObjectBehavior
{
    public function let(
        EventStore $eventStore,
        EventBus $eventBus,
        AggregateFactory $aggregateFactory
    ) {
        $this->beConstructedWith($eventStore, $eventBus, $aggregateFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserStore::class);
        $this->shouldBeAnInstanceOf(EventSourcingRepository::class);
        $this->shouldImplement(UserRepositoryInterface::class);
    }

    public function it_should_store_an_user(
        EventStore $eventStore,
        EventBus $eventBus,
        AggregateFactory $aggregateFactory,
        User $user
    ) {
        $userId = 'id';
        $eventStream = new DomainEventStream([]);
        $user->getAggregateRootId()->willReturn($userId);
        $user->getUncommittedEvents()->willReturn($eventStream);
        $eventStore->append($userId, $eventStream)->shouldBeCalledTimes(1);
        $eventBus->publish($eventStream)->shouldBeCalledTimes(1);

        $this->store($user);
    }

    public function it_should_retrieve_an_user(
        EventStore $eventStore,
        AggregateFactory $aggregateFactory,
        UuidInterface $uuid,
        User $user
    ) {
        $eventStream = new DomainEventStream([]);
        $uuidString = '48bbc105-d8d0-48a3-b7d3-bd8bc2810f57';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $eventStore
            ->load($uuidString)
            ->willReturn($eventStream)->shouldBeCalledTimes(1);
        $aggregateFactory
            ->create(User::class, $eventStream)
            ->willReturn($user)
            ->shouldBeCalledTimes(1);

        $this->get($uuid)->shouldBe($user);
    }

    public function it_should_throw_unexpected_value_exception_if_invalid_user_type(
        EventStore $eventStore,
        AggregateFactory $aggregateFactory,
        UuidInterface $uuid,
        EventSourcedAggregateRoot $user
    ) {
        $eventStream = new DomainEventStream([]);
        $uuidString = '48bbc105-d8d0-48a3-b7d3-bd8bc2810f57';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $eventStore
            ->load($uuidString)
            ->willReturn($eventStream)->shouldBeCalledTimes(1);
        $aggregateFactory
            ->create(User::class, $eventStream)
            ->willReturn($user)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(\UnexpectedValueException::class)
            ->during('get', [$uuid]);
    }
}
