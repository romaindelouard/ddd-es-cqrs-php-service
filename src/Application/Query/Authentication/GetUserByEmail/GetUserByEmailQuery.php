<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail;

use Assert\AssertionFailedException;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class GetUserByEmailQuery implements QueryInterface
{
    public Email $email;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
