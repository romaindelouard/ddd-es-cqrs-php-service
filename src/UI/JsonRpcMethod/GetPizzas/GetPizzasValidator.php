<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod\GetPizzas;

use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\AbstractParamsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class GetPizzasValidator extends AbstractParamsValidator
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
        return ['page', 'limit'];
    }
}
