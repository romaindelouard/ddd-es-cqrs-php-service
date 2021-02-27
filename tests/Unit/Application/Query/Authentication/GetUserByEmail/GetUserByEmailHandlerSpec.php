<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail\GetUserByEmailHandler;
use Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail\GetUserByEmailQuery;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\GetUserCredentialsByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;

class GetUserByEmailHandlerSpec extends ObjectBehavior
{
    public function let(GetUserCredentialsByEmailInterface $userCredentialsByEmail)
    {
        $this->beConstructedWith($userCredentialsByEmail);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetUserByEmailHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    public function it_should_handle_a_query(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        GetUserByEmailQuery $query,
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $emailString = 'toto@domain.com';
        $query->email = $email;
        $email->__toString()->willReturn($emailString);
        $email->toString()->willReturn($emailString);
        $hashedPassword->toString()->willReturn('mypassword');

        $userCredentialsByEmail->getCredentialsByEmail($emailString)
            ->willReturn([$uuid, $email, $hashedPassword])
            ->shouldBeCalledTimes(1);

        $authentication = $this->__invoke($query);

        $authentication->shouldHaveType(Authentication::class);
        $authentication->getUsername()->shouldBe($emailString);
        $authentication->getPassword()->shouldBeString();
    }
}
