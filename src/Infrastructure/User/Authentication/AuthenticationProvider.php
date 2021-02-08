<?php

namespace Romaind\PizzaStore\Infrastructure\User\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class AuthenticationProvider
{
    private JWTTokenManagerInterface $JWTManager;

    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }

    public function generateToken(UuidInterface $uuid, Email $email, HashedPassword $hashedPassword): string
    {
        $auth = Authentication::create($uuid, $email, $hashedPassword);

        return $this->JWTManager->create($auth);
    }
}
