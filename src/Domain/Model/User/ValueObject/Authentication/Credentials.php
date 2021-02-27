<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication;

use Doctrine\ORM\Mapping as ORM;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

/**
 * @ORM\Embeddable
 */
class Credentials
{
    /**
     * @ORM\Column(type="email", unique=true)
     */
    public Email $email;

    /**
     * @ORM\Column(type="hashed_password")
     */
    public HashedPassword $password;

    public function __construct(Email $email, HashedPassword $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
