<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Authentication\GetToken;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenHandler;
use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenQuery;
use Romaind\PizzaStore\Domain\Model\User\Repository\GetUserCredentialsByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Authentication\AuthenticationProvider;

class GetTokenHandlerSpec extends ObjectBehavior
{
    public function let(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider
    ) {
        $this->beConstructedWith($userCredentialsByEmail, $authenticationProvider);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetTokenHandler::class);
    }

    public function it_should_handle(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider,
        GetTokenQuery $query,
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $emailString = 'toto@domain.com';
        $query->email = $email;
        $email->__toString()->willReturn($emailString);

        $userCredentialsByEmail->getCredentialsByEmail($emailString)
            ->willReturn([$uuid, $email, $hashedPassword])
            ->shouldBeCalledTimes(1);
        $authenticationProvider->generateToken($uuid, $email, $hashedPassword)
            ->willReturn('mytocken')
            ->shouldBeCalledTimes(1);

        $this->__invoke($query);
    }
}
