<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\SignUp;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpHandler;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class SignUpHandlerSpec extends ObjectBehavior
{
    public function let(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->beConstructedWith($userRepository, $uniqueEmailSpecification);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SignUpHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    public function it_should_create_and_store_an_user(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        SignUpCommand $command,
        Credentials $credentials,
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $uuidString = 'f43555d6-a7c6-47a9-bc1c-6740e5725af2';
        $uuid->toString()->willReturn($uuidString);
        $credentials->email = $email;
        $credentials->password = $hashedPassword;
        $command->uuid = $uuid;
        $command->credentials = $credentials;

        $uniqueEmailSpecification->isUnique($email)
            ->willReturn(true)->shouldBeCalledTimes(1);
        $userRepository
            ->store(Argument::allOf(
                Argument::type(User::class)
            ))
            ->shouldBeCalledTimes(1);

        $this->__invoke($command);
    }
}
