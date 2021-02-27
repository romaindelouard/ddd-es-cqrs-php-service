<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command\User\ChangeEmail;

use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class ChangeEmailCommand implements CommandInterface
{
    public UuidInterface $userUuid;
    public Email $email;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $userUuid, string $email)
    {
        $this->userUuid = Uuid::fromString($userUuid);
        $this->email = Email::fromString($email);
    }
}
