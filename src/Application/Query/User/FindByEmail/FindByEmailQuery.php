<?php

namespace Romaind\PizzaStore\Application\Query\User\FindByEmail;

use Assert\AssertionFailedException;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class FindByEmailQuery implements QueryInterface
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
