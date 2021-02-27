<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\ComboPizza;

use Assert\Assertion;
use Money\Money;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\Product\ProductInterface;

/**
 * Design pattern composite example: leaf (ComboPizza & Pizza).
 */
class ComboPizza implements ProductInterface
{
    public UuidInterface $uuid;
    public string $name;
    private array $pizzas;

    public function getPizzas(): array
    {
        return $this->pizzas;
    }

    public function __construct(UuidInterface $uuid, string $name, array $pizzas)
    {
        $this->checkPizzaType($pizzas);
        $this->checkPizzasNumber($pizzas);

        $this->uuid = $uuid;
        $this->name = $name;
        $this->pizzas = $pizzas;
    }

    private function checkPizzaType(array $pizzas): bool
    {
        return Assertion::allIsInstanceOf($pizzas, ProductInterface::class);
    }

    private function checkPizzasNumber(array $pizzas): bool
    {
        return Assertion::min(count($pizzas), 2);
    }

    public function getUnitPrice(): Money
    {
        $totalPrice = $this->pizzas[0]->getUnitPrice();
        $max = count($this->pizzas);
        for ($i = 1; $i < $max; ++$i) {
            $totalPrice = $totalPrice->add($this->pizzas[$i]->getUnitPrice());
        }

        return $totalPrice;
    }
}
