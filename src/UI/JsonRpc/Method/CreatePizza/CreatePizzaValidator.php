<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CreatePizzaValidator extends AbstractParamsValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CreatePizzaConstraint) {
            throw new UnexpectedTypeException($constraint, CreatePizzaConstraint::class);
        }

        parent::validate($value, $constraint);
    }

    protected function getMandatoryParams(): array
    {
        return ['name', 'description'];
    }
}
