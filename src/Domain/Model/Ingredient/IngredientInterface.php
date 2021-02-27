<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\Ingredient;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface IngredientInterface
{
    public static function create(UuidInterface $uuid, string $name, Money $unitPrice): Ingredient;
}
