<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Event\User;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Shared\Exception\DateTimeException;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserWasCreated implements Serializable
{
    public UuidInterface $uuid;
    public Credentials $credentials;
    public DateTime $createdAt;

    public function __construct(
        UuidInterface $uuid,
        Credentials $credentials,
        DateTime $createdAt
    ) {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'credentials');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            ),
            DateTime::fromString($data['created_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'credentials' => [
                'email' => $this->credentials->email->toString(),
                'password' => $this->credentials->password->toString(),
            ],
            'created_at' => $this->createdAt->toString(),
        ];
    }
}
