<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository\AbstractPostgresRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;

class PostgresReadModelUserRepositorySpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PostgresReadModelUserRepository::class);
        $this->shouldBeAnInstanceOf(AbstractPostgresRepository::class);
    }
}
