<?php

namespace Romaind\PizzaStore\Domain\Model\Shared\Specification;

/**
 * Implement Specification design pattern.
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    /**
     * @param mixed $value
     */
    abstract public function isSatisfiedBy($value): bool;
}
