<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository\UserEntityRepository;

class UserEntityRepositorySpec extends ObjectBehavior
{
    public function let(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ClassMetadata $classMetadata
    ) {
        $registry->getManagerForClass(User::class)
            ->willReturn($manager)->shouldBeCalledTimes(1);
        $manager->getClassMetadata(User::class)
            ->willReturn($classMetadata)->shouldBeCalledTimes(1);

        $this->beConstructedWith($registry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserEntityRepository::class);
        $this->shouldBeAnInstanceOf(ServiceEntityRepository::class);
    }

    public function it_should_return_an_user_on_find_by_username(
        EntityManagerInterface $manager,
        User $user,
        QueryBuilder $queryBuilder,
        AbstractQuery $query
    ) {
        $manager->createQueryBuilder()->willReturn($queryBuilder);
        $queryBuilder->select('u')->willReturn($queryBuilder);
        $queryBuilder->from(Argument::any(), 'u', null)->willReturn($queryBuilder);
        $queryBuilder->andWhere('u.username = :username')->willReturn($queryBuilder);
        $queryBuilder->setParameter('username', 'john')->willReturn($queryBuilder);
        $queryBuilder->getQuery()->willReturn($query);
        $query->getOneOrNullResult()->willReturn($user);

        $this->findOneByUsername('john')->shouldBe($user);
    }
}
