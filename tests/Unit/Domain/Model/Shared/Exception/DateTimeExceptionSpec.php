<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\Shared\Exception;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Shared\Exception\DateTimeException;

class DateTimeExceptionSpec extends ObjectBehavior
{
    public function let(\Exception $e)
    {
        $this->beConstructedWith($e);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DateTimeException::class);
        $this->shouldBeAnInstanceOf(\Exception::class);
    }

    public function it_should_return_a_message()
    {
        $this->getMessage()->shouldBe('Datetime Malformed or not valid');
    }

    public function it_should_return_a_code()
    {
        $this->getCode()->shouldBe(500);
    }
}
