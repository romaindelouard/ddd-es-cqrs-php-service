<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\ChangeEmail;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Romaind\PizzaStore\Application\Command\User\ChangeEmail\ChangeEmailHandler;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class ChangeEmailHandlerSpec extends ObjectBehavior
{
    public function let(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->beConstructedWith($userRepository, $uniqueEmailSpecification);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ChangeEmailHandler::class);
        $this->shouldImplement(CommandHandlerInterface::class);
    }

    public function it_should_change_user_email(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        ChangeEmailCommand $command,
        UuidInterface $uuid,
        Email $email,
        User $user
    ) {
        $command->userUuid = $uuid;
        $command->email = $email;
        $userRepository->get($uuid)
            ->willReturn($user)->shouldBeCalledTimes(1);
        $user->changeEmail($email, $uniqueEmailSpecification)
        ->shouldBeCalledTimes(1);
        $userRepository->store($user)->shouldBeCalledTimes(1);

        $this->__invoke($command);
    }
}
