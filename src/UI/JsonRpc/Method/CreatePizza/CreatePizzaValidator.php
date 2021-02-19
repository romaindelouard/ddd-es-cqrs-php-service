<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsValidator;
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
