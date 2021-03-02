<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
use Romaind\PizzaStore\Infrastructure\User\Authentication\UserProvider;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
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

    public function it_should_refresh_an_user(
        PostgresReadModelUserRepository $userReadModelRepository,
        UserInterface $user,
        Email $email,
        UuidInterface $uuid,
        HashedPassword $hashedPassword
    ) {
        $emailString = 'toto@domain.com';
        $email->toString()->willReturn($emailString);
        $hashedPasswordString = 'hashedPassword';
        $hashedPassword->toString()->willReturn($hashedPasswordString);
        $userReadModelRepository->getCredentialsByEmail(Argument::any())
            ->willReturn([
                $uuid,
                $email,
                $hashedPassword,
            ])
            ->shouldBeCalledTimes(1);

        $user->getUsername()->willReturn($emailString)->shouldBeCalledTimes(1);

        $authentication = $this->refreshUser($user);
        $authentication->shouldHaveType(Authentication::class);
        $authentication->getUsername()->shouldBe($emailString);
        $authentication->getPassword()->shouldBe($hashedPasswordString);
    }

    public function it_should_support_class()
    {
        $this->supportsClass(Authentication::class)->shouldBe(true);
        $this->supportsClass(\stdClass::class)->shouldBe(false);
    }
}
