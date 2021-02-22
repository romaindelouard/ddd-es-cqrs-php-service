<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaValidator;
use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsConstraint;

class CreatePizzaConstraintSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CreatePizzaConstraint::class);
        $this->beAnInstanceOf(AbstractParamsConstraint::class);
    }

    public function it_should_validated_by()
    {
        $this->validatedBy()->shouldBe(CreatePizzaValidator::class);
    }
}
