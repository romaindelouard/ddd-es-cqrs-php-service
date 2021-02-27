<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command\User\SignIn;

use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Domain\Model\User\Repository\CheckUserByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\UserRepositoryInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class SignInHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userStore;
    private CheckUserByEmailInterface $userCollection;

    public function __construct(UserRepositoryInterface $userStore, CheckUserByEmailInterface $userCollection)
    {
        $this->userStore = $userStore;
        $this->userCollection = $userCollection;
    }

    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->uuidFromEmail($command->email);
        $user = $this->userStore->get($uuid);
        $user->signIn($command->plainPassword);
        $this->userStore->store($user);
    }

    private function uuidFromEmail(Email $email): UuidInterface
    {
        $uuid = $this->userCollection->existsEmail($email);

        if (null === $uuid) {
            throw new InvalidCredentialsException();
        }

        return $uuid;
    }
}
