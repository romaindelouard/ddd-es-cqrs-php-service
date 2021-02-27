<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\Shared\Specification;

/**
 * Implement Specification design pattern.
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    abstract public function isSatisfiedBy($value): bool;
}
