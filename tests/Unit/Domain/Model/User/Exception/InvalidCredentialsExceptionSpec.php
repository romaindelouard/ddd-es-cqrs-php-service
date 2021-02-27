<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User\Exception;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;

class InvalidCredentialsExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(InvalidCredentialsException::class);
        $this->shouldBeAnInstanceOf(\RuntimeException::class);
    }
}
