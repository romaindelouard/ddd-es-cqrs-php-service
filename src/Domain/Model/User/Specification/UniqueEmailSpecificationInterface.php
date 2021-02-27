<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\User\Specification;

use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool;
}
