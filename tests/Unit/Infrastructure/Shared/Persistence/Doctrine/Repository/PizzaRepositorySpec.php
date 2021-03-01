<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository;

use Broadway\Domain\DomainEventStream;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
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

    public function it_should_store_a_pizza(
        EntityManagerInterface $entityManager,
        EventStore $eventStore,
        EventBus $eventBus,
        Pizza $pizza
    ) {
        $pizzaUuid = '462ca49a-5499-4956-bfe4-a16a87d2b943';
        $eventStream = new DomainEventStream([]);
        $pizza->getAggregateRootId()
            ->willReturn($pizzaUuid)->shouldBeCalledTimes(2);
        $pizza->getUncommittedEvents()
            ->willReturn($eventStream)->shouldBeCalledTimes(1);

        $eventStore->append($pizzaUuid, $eventStream)->shouldBeCalledTimes(1);
        $eventBus->publish($eventStream)->shouldBeCalledTimes(1);
        $entityManager->persist($pizza)->shouldBeCalledTimes(1);
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->store($pizza);
    }

    public function it_should_get_a_pizza_by_an_identifier(
        EntityManagerInterface $entityManager,
        UuidInterface $uuid,
        Pizza $pizza
    ) {
        $uuidString = 'c6ee6520-fb48-47aa-aab1-119230f49480';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $entityManager->find(Pizza::class, $uuidString)
            ->willReturn($pizza)
            ->shouldBeCalledTimes(1);

        $this->get($uuid)->shouldBe($pizza);
    }

    public function it_should_fetch_pizzas_by_page(
        EntityManagerInterface $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Pizza $pizza1,
        Pizza $pizza2
    ) {
        $pizzas = [$pizza1, $pizza2];

        $entityManager->createQueryBuilder()->willReturn($queryBuilder);
        $queryBuilder->select('pizza')->willReturn($queryBuilder);
        $queryBuilder->from(Pizza::class, 'pizza')->willReturn($queryBuilder);
        $queryBuilder->setFirstResult(8)->willReturn($queryBuilder);
        $queryBuilder->setMaxResults(2)->willReturn($queryBuilder);
        $queryBuilder->getQuery()->willReturn($query);
        $query->getArrayResult()->willReturn($pizzas);
        
        $this->page(5, 2)->shouldbe($pizzas);
    }
}
