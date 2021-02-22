<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasValidator;
use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsConstraint;

class GetPizzasConstraintSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasConstraint::class);
        $this->beAnInstanceOf(AbstractParamsConstraint::class);
    }

    public function it_should_validated_by()
    {
        $this->validatedBy()->shouldBe(GetPizzasValidator::class);
    }
}
