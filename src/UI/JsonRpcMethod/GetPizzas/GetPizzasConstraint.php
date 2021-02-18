<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas;

use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\AbstractParamsConstraint;

class GetPizzasConstraint extends AbstractParamsConstraint
{
    public function validatedBy(): string
    {
        return GetPizzasValidator::class;
    }
}