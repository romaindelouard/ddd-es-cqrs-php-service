<?php

namespace Romaind\PizzaStore\Domain\Model\Shared\Specification;

interface SpecificationInterface
{
    public function isSatisfiedBy($value): bool;
}
