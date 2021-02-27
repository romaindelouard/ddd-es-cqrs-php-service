<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\Ingredient;

use Money\Money;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Product\ProductInterface;

class Ingredient implements IngredientInterface, ProductInterface
{
    public UuidInterface $uuid;
    public string $name;
    public Money $unitPrice;

    public function __construct(
        UuidInterface $uuid,
        string $name,
        Money $unitPrice
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->unitPrice = $unitPrice;
    }

    public static function create(UuidInterface $uuid, string $name, Money $unitPrice): Ingredient
    {
        return new self($uuid, $name, $unitPrice);
    }

    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }
}
