<?php

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\SignUp;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class SignUpCommandSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            '42fa1330-500c-4299-a0c4-8a0d6e79c6c0',
            'joe@domain.com',
            'mypassword'
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SignUpCommand::class);
        $this->shouldImplement(CommandInterface::class);
    }

    public function it_should_create_a_command()
    {
        $uuid = '42fa1330-500c-4299-a0c4-8a0d6e79c6c0';
        $email = 'joe@domain.com';
        $password = 'mypassword';
        $this->beConstructedWith($uuid, $email, $password);
        $this->uuid->shouldHaveType(UuidInterface::class);
        $this->uuid->toString()->shouldReturn($uuid);
        $this->credentials->shouldHaveType(Credentials::class);
        $this->credentials->email->shouldHaveType(Email::class);
        $this->credentials->email->toString()->shouldReturn($email);
        $this->credentials->password->shouldHaveType(HashedPassword::class);
    }
}
