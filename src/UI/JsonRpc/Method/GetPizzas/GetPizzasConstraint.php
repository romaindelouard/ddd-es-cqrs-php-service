<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsConstraint;

class GetPizzasConstraint extends AbstractParamsConstraint
{
    public function validatedBy(): string
    {
        return GetPizzasValidator::class;
    }
}
