<?php

namespace Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\CheckUserByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\GetUserCredentialsByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository\PostgresRepository;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class PostgresReadModelUserRepository extends PostgresRepository implements CheckUserByEmailInterface, GetUserCredentialsByEmailInterface
{
    // @phpstan-ignore-next-line
    protected EntityRepository $repository;

    protected function defineEntityManager(): void
    {
        $objectRepository = $this->entityManager->getRepository(UserView::class);
        if (!$objectRepository instanceof EntityRepository) {
            throw new \UnexpectedValueException('objectRepository is not an EntityRepository');
        }
        $this->repository = $objectRepository;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->toString());

        return $this->oneOrException($qb);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->getUserByEmailQueryBuilder($email)
            ->select('user.uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return $userId['uuid'] ?? null;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmail(Email $email): UserView
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email)
        );
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmailAsArray(Email $email): array
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email)
                ->select('
                user.uuid, 
                user.credentials.email, 
                user.createdAt, 
                user.updatedAt'),
            AbstractQuery::HYDRATE_ARRAY
        );
    }

    public function add(UserView $userRead): void
    {
        $this->register($userRead);
    }

    /**
     * @return array{
     *  0: \Ramsey\Uuid\UuidInterface,
     *  1: Email,
     *  2: \Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword
     * }
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());

        $user = $this->oneOrException($qb, AbstractQuery::HYDRATE_ARRAY);

        return [
            $user['uuid'],
            $user['credentials.email'],
            $user['credentials.password'],
        ];
    }
}
