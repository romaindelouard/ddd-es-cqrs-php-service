<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
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
}
