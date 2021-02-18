<?php


namespace Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;


use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\AbstractParamsConstraint;

class CreatePizzaConstraint extends AbstractParamsConstraint
{
    public function validatedBy(): string
    {
        return CreatePizzaValidator::class;
    }
}