<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Validation;

use Symfony\Component\Validator\Constraint;

abstract class AbstractParamsConstraint extends Constraint
{
    public string $mandatoryParamsMessage = 'Missing at least one mandatory param (%params%).';
    public string $identifierParamsMessage = 'Invalid integer for an identifier (%params%).';
    public string $invalidUuidMessage = '"%value%" is not a valid UUID.';
    public string $invalidDateFormatMessage = '%param% is not a valid date, should be formatted %format%.';
    public string $invalidDateFutureMessage = '%param% should not be in the future.';
    public string $invalidUrlMessage = '"%value%" is not a valid URL.';
}
