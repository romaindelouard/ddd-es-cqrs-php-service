<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;

abstract class PostgresRepository
{
    protected EntityRepository $repository;
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setEntityManager();
    }

    abstract protected function setEntityManager(): void;

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

    public function isHealthy(): bool
    {
        $connection = $this->entityManager->getConnection();

        try {
            $dummySelectSQL = $connection->getDatabasePlatform()->getDummySelectSQL();
            $connection->executeQuery($dummySelectSQL);

            return true;
        } catch (\Throwable $exception) {
            $connection->close();

            return false;
        }
    }
}
