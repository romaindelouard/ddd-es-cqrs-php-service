<?php

namespace Romaind\PizzaStore\Infrastructure\User\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\User;

class UserStore extends EventSourcingRepository implements UserRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(User $user): void
    {
        $this->save($user);
    }

    /**
     * @throw \UnexpectedValueException
     */
    public function get(UuidInterface $uuid): User
    {
        $user = $this->load($uuid->toString());
        if (!$user instanceof User) {
            throw new \UnexpectedValueException('User type is not valid');
        }

        return $user;
    }
}
