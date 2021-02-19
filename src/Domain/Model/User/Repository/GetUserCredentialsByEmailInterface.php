<?php

namespace Romaind\PizzaStore\Domain\Model\User\Repository;

use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

interface GetUserCredentialsByEmailInterface
{
    /**
     * @return array{
     *  0: \Ramsey\Uuid\UuidInterface,
     *  1: Email,
     *  2: \Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword
     * }
     */
    public function getCredentialsByEmail(Email $email): array;
}
