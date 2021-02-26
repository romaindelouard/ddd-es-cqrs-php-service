<?php

namespace Romaind\PizzaStore\Domain\Event;

interface SearchEventRepositoryInterface
{
    public function boot(): void;

    public function search(array $query): array;

    public function page(int $page = 1, int $limit = 50): array;
}
