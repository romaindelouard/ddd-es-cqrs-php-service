<?php

namespace Romaind\PizzaStore\Application\Command\User\ChangeEmail;

use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;

class ChangeEmailHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->userRepository->get($command->userUuid);
        $user->changeEmail($command->email, $this->uniqueEmailSpecification);
        $this->userRepository->store($user);
    }
}
