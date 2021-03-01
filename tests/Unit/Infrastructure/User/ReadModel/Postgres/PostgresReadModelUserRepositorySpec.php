<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository\AbstractPostgresRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class PostgresReadModelUserRepositorySpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,
        EntityRepository $entityRepository
    ) {
        $entityManager->getRepository(UserView::class)
            ->willReturn($entityRepository)->shouldBeCalledTimes(1);
        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PostgresReadModelUserRepository::class);
        $this->shouldBeAnInstanceOf(AbstractPostgresRepository::class);
    }

    public function it_should_get_an_user_by_uuid(
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        UuidInterface $uuid,
        UserView $userView
    ) {
        $uuidString = 'uuid';
        $uuid->toString()->willReturn($uuidString);
        $entityRepository->createQueryBuilder('user')->willReturn($queryBuilder);
        $queryBuilder->where('user.uuid = :uuid')->willReturn($queryBuilder);
        $queryBuilder->setParameter('uuid', $uuidString)->willReturn($queryBuilder);
        $queryBuilder->getQuery()->willReturn($query);
        $query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)->willReturn($userView);

        $this->oneByUuid($uuid)->shouldBe($userView);
    }

    public function it_should_check_if_email_exists(
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        UuidInterface $uuid,
        Email $email
    ) {
        $emailString = 'toto@domain.com';
        $email->toString()->willReturn($emailString)->shouldBeCalledTimes(1);
        $entityRepository->createQueryBuilder('user')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->select('user.uuid')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->where('user.credentials.email = :email')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->setParameter('email', $emailString)
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->getQuery()->willReturn($query)->shouldBeCalledTimes(1);
        $query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
            ->willReturn(['uuid' => $uuid])->shouldBeCalledTimes(1);

        $this->existsEmail($email)->shouldBe($uuid);
    }

    public function it_should_get_an_user_by_email(
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Email $email,
        UserView $userView
    ) {
        $emailString = 'toto@domain.com';
        $email->toString()->willReturn($emailString)->shouldBeCalledTimes(1);
        $entityRepository->createQueryBuilder('user')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->where('user.credentials.email = :email')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->setParameter('email', $emailString)
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->getQuery()->willReturn($query)->shouldBeCalledTimes(1);
        $query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
                ->willReturn($userView)->shouldBeCalledTimes(1);

        $this->oneByEmail($email)->shouldBe($userView);
    }

    public function it_should_get_an_user_as_array_by_email(
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Email $email
    ) {
        $emailString = 'toto@domain.com';
        $email->toString()->willReturn($emailString)->shouldBeCalledTimes(1);
        $entityRepository->createQueryBuilder('user')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->select([
                'user.uuid',
                'user.credentials.email',
                'user.createdAt',
                'user.updatedAt',
            ])
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->where('user.credentials.email = :email')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->setParameter('email', $emailString)
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->getQuery()->willReturn($query)->shouldBeCalledTimes(1);
        $query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
                ->willReturn([])->shouldBeCalledTimes(1);

        $this->oneByEmailAsArray($email)->shouldBe([]);
    }

    public function it_should_add_an_user(
        EntityManagerInterface $entityManager,
        UserView $userView
    ) {
        $entityManager->persist($userView)->shouldBeCalledTimes(1);
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->add($userView);
    }

    public function it_should_get_credentials_by_email(
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Email $email,
        UuidInterface $uuid,
        HashedPassword $hashedPassword
    ) {
        $credentials = [
            'uuid' => $uuid,
            'credentials.email' => $email,
            'credentials.password' => $hashedPassword,
        ];
        $emailString = 'toto@domain.com';
        $email->toString()->willReturn($emailString)->shouldBeCalledTimes(1);
        $entityRepository->createQueryBuilder('user')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->where('user.credentials.email = :email')
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->setParameter('email', $emailString)
            ->willReturn($queryBuilder)->shouldBeCalledTimes(1);
        $queryBuilder->getQuery()->willReturn($query)->shouldBeCalledTimes(1);
        $query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
            ->willReturn($credentials)
            ->shouldBeCalledTimes(1);

        $this->getCredentialsByEmail($email)->shouldBe([
            $uuid,
            $email,
            $hashedPassword,
        ]);
    }
}
