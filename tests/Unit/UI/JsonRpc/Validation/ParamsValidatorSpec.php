<?php

namespace tests\Unit\Romaind\PizzaStore\UI\JsonRpc\Validation;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParamsValidatorSpec extends ObjectBehavior
{
    public function let(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->beConstructedWith($validator, $logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ParamsValidator::class);
    }
}
