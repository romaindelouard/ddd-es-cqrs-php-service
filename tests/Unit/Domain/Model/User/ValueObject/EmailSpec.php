<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User\ValueObject;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class EmailSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('fromString', ['toto@email.com']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Email::class);
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
    }

    public function it_should_serializeand_return_a_json_value()
    {
        $this->beConstructedThrough('fromString', ['toto@domain.com']);
        $this->jsonSerialize()->shouldBe('toto@domain.com');
    }
}
