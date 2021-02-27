<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\User\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class AuthenticationProvider
{
    private JWTTokenManagerInterface $jwtTokenManager;

    public function __construct(JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function generateToken(
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ): string {
        $user = Authentication::create($uuid, $email, $hashedPassword);

        return $this->jwtTokenManager->create($user);
    }
}
