<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsConstraint;

class CreatePizzaConstraint extends AbstractParamsConstraint
{
    public function validatedBy(): string
    {
        return CreatePizzaValidator::class;
    }
}
