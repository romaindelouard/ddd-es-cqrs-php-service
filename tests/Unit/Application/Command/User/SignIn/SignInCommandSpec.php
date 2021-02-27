<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\SignIn;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInCommand;

class SignInCommandSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('joe@domain.com', 'mypassword');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SignInCommand::class);
        $this->shouldImplement(CommandInterface::class);
    }

    public function it_should_create_a_command()
    {
        $this->beConstructedWith('joe@domain.com', 'mypassword');
        $this->plainPassword->shouldBe('mypassword');
        $this->email->toString()->shouldBe('joe@domain.com');
    }
}
