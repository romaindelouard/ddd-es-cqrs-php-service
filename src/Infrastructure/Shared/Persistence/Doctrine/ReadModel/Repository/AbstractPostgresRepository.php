<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;

abstract class AbstractPostgresRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->defineEntityManager();
    }

    abstract protected function defineEntityManager(): void;

    /**
     * @param mixed $model
     */
    public function register($model): void
    {
        $this->entityManager->persist($model);
        $this->apply();
    }

    public function apply(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @return mixed
     *
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    protected function oneOrException(QueryBuilder $queryBuilder, int $hydration = AbstractQuery::HYDRATE_OBJECT)
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult($hydration);

        if (null === $model) {
            throw new NotFoundException();
        }

        return $model;
    }
}
