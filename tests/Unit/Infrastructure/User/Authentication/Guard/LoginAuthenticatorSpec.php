<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication\Guard;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Command\MessengerCommandBus;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Query\MessengerQueryBus;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Guard\LoginAuthenticator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginAuthenticatorSpec extends ObjectBehavior
{
    public function let(
        MessengerCommandBus $commandBus,
        MessengerQueryBus $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->beConstructedWith($commandBus, $queryBus, $router);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LoginAuthenticator::class);
        $this->shouldBeAnInstanceOf(AbstractFormLoginAuthenticator::class);
    }
}
