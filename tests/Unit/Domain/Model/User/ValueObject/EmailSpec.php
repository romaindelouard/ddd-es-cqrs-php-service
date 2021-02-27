<?php

declare(strict_types=1);

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
        $emailString = 'toto@domain.com';
        $this->beConstructedThrough('fromString', [$emailString]);
        $this->jsonSerialize()->shouldBe($emailString);
        $this->__toString()->shouldBe($emailString);
        $this->toString()->shouldBe($emailString);
    }
}
