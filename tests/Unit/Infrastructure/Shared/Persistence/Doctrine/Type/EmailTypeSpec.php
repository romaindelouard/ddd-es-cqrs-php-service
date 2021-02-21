<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\StringType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\EmailType;

class EmailTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailType::class);
        $this->shouldBeAnInstanceOf(StringType::class);
    }
}
