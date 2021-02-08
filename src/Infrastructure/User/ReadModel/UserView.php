<?php

namespace Romaind\PizzaStore\Infrastructure\User\ReadModel;

use Assert\AssertionFailedException;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Shared\Exception\DateTimeException;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

/**
 * @ORM\Entity
 * @ORM\Table(name="public.user")
 */
class UserView implements SerializableReadModel
{
    public const TYPE = 'UserView';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Embedded(class="Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials")
     * */
    private Credentials $credentials;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTime $updatedAt;

    private function __construct(
        UuidInterface $uuid,
        Credentials $credentials,
        DateTime $createdAt,
        ?DateTime $updatedAt
    ) {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @return UserView
     *
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'] ?? '')
            ),
            DateTime::fromString($data['created_at']),
            isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->getId(),
            'credentials' => [
                'email' => (string) $this->credentials->email,
            ],
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return (string) $this->credentials->email;
    }

    public function changeEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
