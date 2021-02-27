<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\Pizza;

use Ramsey\Uuid\UuidInterface;

interface PizzaRepositoryInterface
{
    public function store(Pizza $pizza): void;

    public function get(UuidInterface $uuid): ?Pizza;

    public function page(int $currentPage, int $itemPerPage): array;
}
