<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command\User\SignUp;

use Ramsey\Uuid\Exception\DateTimeException;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\User;

class SignUpHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface  $userRepository;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create(
            $command->uuid,
            $command->credentials,
            $this->uniqueEmailSpecification
        );

        $this->userRepository->store($user);
    }
}
