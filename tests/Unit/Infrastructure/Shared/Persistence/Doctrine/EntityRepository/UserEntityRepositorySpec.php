<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository\UserEntityRepository;

class UserEntityRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry)
    {
        $this->beConstructedWith($registry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserEntityRepository::class);
        $this->shouldBeAnInstanceOf(ServiceEntityRepository::class);
    }
}
