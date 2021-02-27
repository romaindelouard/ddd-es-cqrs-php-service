<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\ReadModel\Projector;

use Broadway\ReadModel\Projector;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Projector\UserProjectionFactory;

class UserProjectionFactorySpec extends ObjectBehavior
{
    public function let(PostgresReadModelUserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserProjectionFactory::class);
        $this->shouldBeAnInstanceOf(Projector::class);
    }
}
