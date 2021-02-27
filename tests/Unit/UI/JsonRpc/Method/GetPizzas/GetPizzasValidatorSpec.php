<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\GetPizzas\GetPizzasValidator;
use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class GetPizzasValidatorSpec extends ObjectBehavior
{
    protected $context;

    public function let(ExecutionContextInterface $context)
    {
        $this->initialize($context);
        $this->context = $context;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasValidator::class);
        $this->shouldBeAnInstanceOf(AbstractParamsValidator::class);
    }

    public function it_validates_valid_parameters(GetPizzasConstraint $constraint)
    {
        $this->context->buildViolation(Argument::cetera())->shouldNotBeCalled();
        $this->context->getViolations()->shouldNotBeCalled();

        $this->validate(['page' => 1, 'limit' => 10], $constraint);
    }

    public function it_throws_an_unexpected_type_exception(Constraint $constraint)
    {
        $this->context->buildViolation(Argument::cetera())->shouldNotBeCalled();
        $this->context->getViolations()->shouldNotBeCalled();

        $this->shouldThrow(UnexpectedTypeException::class)->during(
            'validate',
            [
                ['page' => 1, 'limit' => 10],
                $constraint,
            ]
        );
    }
}
