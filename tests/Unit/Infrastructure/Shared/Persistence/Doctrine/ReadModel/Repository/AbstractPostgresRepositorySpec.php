<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\ReadModel\Repository\AbstractPostgresRepository;

class AbstractPostgresRepositorySpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManager)
    {
        $this->beAnInstanceOf(AbstractPostgresRepositoryMock::class);
        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AbstractPostgresRepository::class);
    }

    public function it_should_register_a_model(
        EntityManagerInterface $entityManager,
        \stdClass $model
    ) {
        $entityManager->persist($model)->shouldBeCalledTimes(1);
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->register($model);
    }
}

class AbstractPostgresRepositoryMock extends AbstractPostgresRepository
{
    protected function defineEntityManager(): void
    {
        return;
    }
}
