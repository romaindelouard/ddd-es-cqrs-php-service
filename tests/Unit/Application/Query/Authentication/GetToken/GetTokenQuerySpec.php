<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\Authentication\GetToken;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenQuery;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class GetTokenQuerySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('johny@cash.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetTokenQuery::class);
        $this->shouldImplement(QueryInterface::class);
    }

    public function it_should_create_a_query()
    {
        $email = 'johny@cash.com';
        $this->beConstructedWith($email);
        $this->email->shouldHaveType(Email::class);
        $this->email->toString()->shouldBe($email);
    }
}
