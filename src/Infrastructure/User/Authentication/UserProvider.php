<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\User\Authentication;

use Assert\AssertionFailedException;
use Doctrine\ORM\NonUniqueResultException;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private PostgresReadModelUserRepository $userReadModelRepository;

    public function __construct(PostgresReadModelUserRepository $userReadModelRepository)
    {
        $this->userReadModelRepository = $userReadModelRepository;
    }

    /**
     * @return Authentication|UserInterface
     *
     * @throws AssertionFailedException
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function loadUserByUsername(string $email)
    {
        [$uuid, $email, $hashedPassword] = $this->userReadModelRepository->getCredentialsByEmail(
            Email::fromString($email)
        );

        return Authentication::create($uuid, $email, $hashedPassword);
    }

    /**
     * @throws NotFoundException
     * @throws AssertionFailedException
     * @throws NonUniqueResultException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return Authentication::class === $class;
    }
}
