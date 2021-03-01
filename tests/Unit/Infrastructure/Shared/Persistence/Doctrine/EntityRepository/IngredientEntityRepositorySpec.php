<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Ingredient\Ingredient;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository\IngredientEntityRepository;

class IngredientEntityRepositorySpec extends ObjectBehavior
{
    public function let(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ClassMetadata $classMetadata
    ) {
        $registry->getManagerForClass(Ingredient::class)
            ->willReturn($manager)->shouldBeCalledTimes(1);
        $manager->getClassMetadata(Ingredient::class)
            ->willReturn($classMetadata)->shouldBeCalledTimes(1);

        $this->beConstructedWith($registry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(IngredientEntityRepository::class);
        $this->shouldBeAnInstanceOf(ServiceEntityRepository::class);
    }
}
