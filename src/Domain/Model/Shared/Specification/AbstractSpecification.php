<?php

namespace Romaind\PizzaStore\Domain\Model\Shared\Specification;

abstract class AbstractSpecification
{
    /**
     * @param mixed $value
     */
    abstract public function isSatisfiedBy($value): bool;
}
