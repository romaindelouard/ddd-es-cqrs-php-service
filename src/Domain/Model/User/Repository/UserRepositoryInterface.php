<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\User\Repository;

use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\User;

interface UserRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
