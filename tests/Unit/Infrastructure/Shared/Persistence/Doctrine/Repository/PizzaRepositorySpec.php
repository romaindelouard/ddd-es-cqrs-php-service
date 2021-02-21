<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository\PizzaRepository;

class PizzaRepositorySpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,
        EventStore $eventStore,
        EventBus $eventBus
    ) {
        $this->beConstructedWith($entityManager, $eventStore, $eventBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PizzaRepository::class);
        $this->shouldBeAnInstanceOf(EventSourcingRepository::class);
        $this->shouldImplement(PizzaRepositoryInterface::class);
    }
}
