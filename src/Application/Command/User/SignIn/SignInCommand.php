<?php

namespace Romaind\PizzaStore\Application\Command\User\SignIn;

use Assert\AssertionFailedException;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class SignInCommand implements CommandInterface
{
    public Email $email;
    public string $plainPassword;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}
