<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Repository;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Domain\Model\Pizza\PizzaRepositoryInterface;

class PizzaRepository extends EventSourcingRepository implements PizzaRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Pizza::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
        $this->entityManager = $entityManager;
    }

    public function store(Pizza $pizza): void
    {
        $this->save($pizza);
        $this->entityManager->persist($pizza);
        $this->entityManager->flush();
    }

    public function get(UuidInterface $uuid): ?Pizza
    {
        return $this->entityManager->find(Pizza::class, $uuid->toString());
    }

    public function page(int $currentPage, int $itemPerPage): array
    {
        $offset = $itemPerPage * ($currentPage - 1);

        return $this->entityManager->createQueryBuilder()
            ->select('pizza')
            ->from(Pizza::class, 'pizza')
            ->setFirstResult($offset)
            ->setMaxResults($itemPerPage)
            ->getQuery()
            ->getArrayResult();
    }
}
