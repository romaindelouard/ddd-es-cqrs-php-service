<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository;

interface ElasticSearchRepositoryInterface
{
    public function search(array $query): array;

    public function page(int $page = 1, int $limit = 50): array;

    public function isHealthly(): bool;
}
