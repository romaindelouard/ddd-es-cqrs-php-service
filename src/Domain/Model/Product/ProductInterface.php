<?php

namespace Romaind\PizzaStore\Domain\Model\Product;

use Money\Money;

/**
 * Design pattern example: component (ComboPizza & Pizza).
 */
interface ProductInterface
{
    public function getUnitPrice(): Money;
}
