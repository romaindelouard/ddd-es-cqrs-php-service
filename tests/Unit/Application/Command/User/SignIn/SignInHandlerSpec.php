<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\SignIn;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInCommand;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInHandler;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Domain\Model\User\Repository\CheckUserByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class SignInHandlerSpec extends ObjectBehavior
{
    public function let(
        UserRepositoryInterface $userStore,
        CheckUserByEmailInterface $userCollection
    ) {
        $this->beConstructedWith($userStore, $userCollection);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SignInHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    public function it_should_invoke(
        UserRepositoryInterface $userStore,
        CheckUserByEmailInterface $userCollection,
        SignInCommand $command,
        Email $email,
        UuidInterface $uuid,
        User $user
    ) {
        $command->email = $email;
        $command->plainPassword = 'mypassword';
        $userCollection->existsEmail($email)
            ->willReturn($uuid)->shouldBeCalledTimes(1);
        $userStore->get($uuid)->willReturn($user)->shouldBeCalledTimes(1);
        $user->signIn('mypassword')->shouldBeCalledTimes(1);
        $userStore->store($user)->shouldBeCalledTimes(1);

        $this->__invoke($command);
    }

    public function it_should_throw_an_invalid_credentials_exception(
        UserRepositoryInterface $userStore,
        CheckUserByEmailInterface $userCollection,
        SignInCommand $command,
        Email $email,
        User $user
    ) {
        $command->email = $email;
        $command->plainPassword = 'mypassword';
        $userCollection->existsEmail($email)
            ->willReturn(null)->shouldBeCalledTimes(1);
        $userStore->get(Argument::any())->shouldNotBeCalled();
        $user->signIn(Argument::any())->shouldNotBeCalled();
        $userStore->store(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(InvalidCredentialsException::class)
            ->during('__invoke', [$command]);
    }
}
