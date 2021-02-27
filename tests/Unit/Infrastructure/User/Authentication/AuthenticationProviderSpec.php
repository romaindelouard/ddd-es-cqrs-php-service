<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
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

    public function it_should_generate_a_token(
        JWTTokenManagerInterface $JWTManager,
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $emailString = 'toto@gmail.com';
        $email->toString()->willReturn($emailString);
        $JWTManager
            ->create(Argument::allOf(
                Argument::type(Authentication::class),
                Argument::that(function ($authentication) use ($emailString) {
                    if ($emailString !== $authentication->getUsername()) {
                        return false;
                    }

                    return true;
                })
            ))
            ->willReturn('mytoken')
            ->shouldBeCalledTimes(1);

        $this->generateToken($uuid, $email, $hashedPassword)
            ->shouldBe('mytoken');
    }
}
