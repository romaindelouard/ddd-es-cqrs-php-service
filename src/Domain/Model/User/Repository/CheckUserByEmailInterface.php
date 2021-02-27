<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\User\Repository;

use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UuidInterface;
}
