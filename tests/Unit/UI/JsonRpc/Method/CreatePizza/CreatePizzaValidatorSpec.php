<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaConstraint;
use Romaind\PizzaStore\UI\JsonRpc\Method\CreatePizza\CreatePizzaValidator;
use Romaind\PizzaStore\UI\JsonRpc\Validation\AbstractParamsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CreatePizzaValidatorSpec extends ObjectBehavior
{
    protected $context;

    public function let(ExecutionContextInterface $context)
    {
        $this->initialize($context);
        $this->context = $context;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreatePizzaValidator::class);
        $this->shouldBeAnInstanceOf(AbstractParamsValidator::class);
    }

    public function it_validates_valid_parameters(CreatePizzaConstraint $constraint)
    {
        $this->context->buildViolation(Argument::cetera())->shouldNotBeCalled();
        $this->context->getViolations()->shouldNotBeCalled();

        $this->validate(['name' => '4 cheeses', 'description' => 'mega pizza'], $constraint);
    }

    public function it_throws_an_unexpected_type_exception(Constraint $constraint)
    {
        $this->context->buildViolation(Argument::cetera())->shouldNotBeCalled();
        $this->context->getViolations()->shouldNotBeCalled();

        $this->shouldThrow(UnexpectedTypeException::class)->during(
            'validate',
            [
                ['name' => '4 cheeses', 'description' => 'mega pizza'],
                $constraint,
            ]
        );
    }
}
