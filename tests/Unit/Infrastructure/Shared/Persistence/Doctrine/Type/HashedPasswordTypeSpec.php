<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\StringType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\HashedPasswordType;

class HashedPasswordTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HashedPasswordType::class);
        $this->shouldBeAnInstanceOf(StringType::class);
    }
}
