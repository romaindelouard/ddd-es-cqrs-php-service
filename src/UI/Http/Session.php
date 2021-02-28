<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http;

use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Session
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): Authentication
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            throw new InvalidCredentialsException();
        }

        $user = $token->getUser();

        if (!$user instanceof Authentication) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }
}
