<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail\GetUserByEmailQuery;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class GetUserByEmailQuerySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('toto@domain.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetUserByEmailQuery::class);
        $this->shouldImplement(QueryInterface::class);
    }

    public function it_should_create_query()
    {
        $email = 'toto@domain.com';
        $this->beConstructedWith($email);
        $this->email->shouldHaveType(Email::class);
        $this->email->toString()->shouldBe($email);
    }
}
