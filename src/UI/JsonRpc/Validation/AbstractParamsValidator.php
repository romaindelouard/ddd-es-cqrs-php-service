<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\JsonRpc\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class AbstractParamsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AbstractParamsConstraint) {
            throw new UnexpectedTypeException($constraint, AbstractParamsConstraint::class);
        }
        $this->checkMandatoryParams($value, $constraint);
    }

    protected function checkMandatoryParams(array $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AbstractParamsConstraint) {
            throw new UnexpectedTypeException($constraint, AbstractParamsConstraint::class);
        }

        foreach ($this->getMandatoryParams() as $mandatory) {
            if (empty($value[$mandatory])) {
                $this->context->buildViolation($constraint->mandatoryParamsMessage)
                    ->setParameter('%params%', implode(', ', $this->getMandatoryParams()))
                    ->setInvalidValue($value)
                    ->addViolation();
            }
        }
    }

    protected function getMandatoryParams(): array
    {
        return [];
    }
}
