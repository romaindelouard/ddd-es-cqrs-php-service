<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
use Romaind\PizzaStore\UI\Http\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SessionSpec extends ObjectBehavior
{
    public function let(TokenStorageInterface $tokenStorage)
    {
        $this->beConstructedWith($tokenStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Session::class);
    }

    public function it_should_return_a_session_user(
        TokenStorageInterface $tokenStorage,
        Authentication $authentication,
        TokenInterface $token
    ) {
        $tokenStorage->getToken()
            ->willReturn($token)->shouldBeCalledTimes(1);
        $token->getUser()
                ->willReturn($authentication)->shouldBeCalledTimes(1);

        $this->get()->shouldBe($authentication);
    }

    public function it_throws_invalid_credentials_exception_if_no_token(
        TokenStorageInterface $tokenStorage
    ) {
        $tokenStorage->getToken()
            ->willReturn(null)->shouldBeCalledTimes(1);

        $this->shouldThrow(InvalidCredentialsException::class)->during('get', []);
    }

    public function it_throws_invalid_credentials_exception_if_no_authentication_user(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token
    ) {
        $tokenStorage->getToken()
            ->willReturn($token)->shouldBeCalledTimes(1);
        $token->getUser()
            ->willReturn('')->shouldBeCalledTimes(1);

        $this->shouldThrow(InvalidCredentialsException::class)->during('get', []);
    }
}
