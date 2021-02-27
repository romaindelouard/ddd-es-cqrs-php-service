<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\Shared\ValueObject;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Shared\Exception\DateTimeException;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;

class DateTimeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DateTime::class);
        $this->shouldBeAnInstanceOf(\DateTimeImmutable::class);
    }

    public function it_should_create_a_datetime_with_a_string_value()
    {
        $dateTimeObject = $this::fromString('2021-02-18T15:14:56.000000+00:00');
        $dateTimeObject->shouldHaveType(DateTime::class);
        $dateTimeObject->toString()->shouldBe('2021-02-18T15:14:56.000000+00:00');
    }

    public function it_should_throw_datetime_exception_with_bad_value()
    {
        $this->shouldThrow(DateTimeException::class)->during('fromString', ['bad value']);
    }
}
