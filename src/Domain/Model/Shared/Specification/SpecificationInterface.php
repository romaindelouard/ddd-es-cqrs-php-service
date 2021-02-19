<?php

namespace Romaind\PizzaStore\Domain\Model\Shared\Specification;

interface SpecificationInterface
{
    /**
     * @param mixed $value
     */
    public function isSatisfiedBy($value): bool;
}
