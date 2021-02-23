<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\User\Authentication\UserProvider;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProviderSpec extends ObjectBehavior
{
    public function let(PostgresReadModelUserRepository $userReadModelRepository)
    {
        $this->beConstructedWith($userReadModelRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserProvider::class);
        $this->shouldImplement(UserProviderInterface::class);
    }
}
