<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\DateTimeImmutableType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\DateTimeType;

class DateTimeTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DateTimeType::class);
        $this->shouldBeAnInstanceOf(DateTimeImmutableType::class);
    }
}
