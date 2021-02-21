<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\User\Authentication\AuthenticationProvider;

class AuthenticationProviderSpec extends ObjectBehavior
{
    public function let(JWTTokenManagerInterface $JWTManager)
    {
        $this->beConstructedWith($JWTManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticationProvider::class);
    }
}
