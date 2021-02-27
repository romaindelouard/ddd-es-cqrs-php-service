<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\User\ChangeEmail;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class ChangeEmailCommandSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('d1d378fc-41be-404e-b2e7-306a97180c90', 'joe@domain.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ChangeEmailCommand::class);
        $this->shouldImplement(CommandInterface::class);
    }

    public function it_should_create_a_command()
    {
        $email = 'joe@domain.com';
        $uuidString = 'd1d378fc-41be-404e-b2e7-306a97180c90';
        $this->beConstructedWith($uuidString, $email);
        $this->userUuid->shouldHaveType(UuidInterface::class);
        $this->email->shouldHaveType(Email::class);

        $this->email->toString()->shouldBe($email);
        $this->userUuid->toString()->shouldBe($uuidString);
    }
}
