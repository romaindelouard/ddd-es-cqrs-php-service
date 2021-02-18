<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;

use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\AbstractParamsValidator;
use Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas\GetPizzasConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CreatePizzaValidator extends AbstractParamsValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof GetPizzasConstraint) {
            throw new UnexpectedTypeException($constraint, GetPizzasConstraint::class);
        }

        parent::validate($value, $constraint);
    }

    protected function getMandatoryParams(): array
    {
        return ['name', 'description'];
    }
}
