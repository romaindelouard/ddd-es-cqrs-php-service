<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;

class HashedPasswordSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('encode', ['mypassword']);
    }

    public function it_is_initializable()
    {
        global $mockPasswordHashAndReturnFalse;
        $mockPasswordHashAndReturnFalse = false;
        $this->shouldHaveType(HashedPassword::class);
    }

    public function it_should_create_and_encode_a_plain_password()
    {
        $this->beConstructedThrough('encode', ['mypassword']);
        $this->match('mypassword')->shouldBe(true);
    }

    public function it_should_create_from_a_hashed_password()
    {
        $this->beConstructedThrough('fromHash', ['myhashedpassword']);
        $this->toString()->shouldBe('myhashedpassword');
        $this->__toString()->shouldBe('myhashedpassword');
    }

    public function it_should_throw_if_invalid_password_length()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('encode', ['123']);
    }

    public function it_should_mathch_and_return_false_if_invalid_password()
    {
        $this->beConstructedThrough('encode', ['mypassword']);
        $this->match('invalidpassword')->shouldBe(false);
    }
}
