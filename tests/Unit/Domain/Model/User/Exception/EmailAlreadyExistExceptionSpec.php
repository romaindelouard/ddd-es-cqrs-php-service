<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User\Exception;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;

class EmailAlreadyExistExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailAlreadyExistException::class);
        $this->shouldBeAnInstanceOf(\InvalidArgumentException::class);
    }

    public function it_should_return_a_message()
    {
        $this->getMessage()->shouldBe('Email already registered.');
    }
}
