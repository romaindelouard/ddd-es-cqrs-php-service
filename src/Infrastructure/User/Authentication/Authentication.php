<?php

namespace Romaind\PizzaStore\Infrastructure\User\Authentication;

use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Authentication implements UserInterface, EncoderAwareInterface
{
    private UuidInterface $uuid;
    private Email $email;
    private HashedPassword $hashedPassword;

    private function __construct(UuidInterface $uuid, Email $email, HashedPassword $hashedPassword)
    {
        $this->uuid = $uuid;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public static function create(UuidInterface $uuid, Email $email, HashedPassword $hashedPassword): self
    {
        return new self($uuid, $email, $hashedPassword);
    }

    public function getUsername(): string
    {
        return $this->email->toString();
    }

    public function getPassword(): string
    {
        return $this->hashedPassword->toString();
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // noop
    }

    public function getEncoderName(): string
    {
        return 'bcrypt';
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->email->toString();
    }
}
