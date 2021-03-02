<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository\PizzaEntityRepository;

class PizzaEntityRepositorySpec extends ObjectBehavior
{
    public function let(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ClassMetadata $classMetadata
    ) {
        $registry->getManagerForClass(Pizza::class)
            ->willReturn($manager)->shouldBeCalledTimes(1);
        $manager->getClassMetadata(Pizza::class)
            ->willReturn($classMetadata)->shouldBeCalledTimes(1);

        $this->beConstructedWith($registry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PizzaEntityRepository::class);
        $this->shouldBeAnInstanceOf(ServiceEntityRepository::class);
    }
}
