<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Bus\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Command\MessengerCommandBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBusSpec extends ObjectBehavior
{
    public function let(MessageBusInterface $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessengerCommandBus::class);
        $this->shouldImplement(CommandBusInterface::class);
    }

    public function it_should_handle_a_command(
        MessageBusInterface $messageBus,
        CommandInterface $command
    ) {
        $messageBus->dispatch($command)
            ->willReturn(new Envelope(new \stdClass()))
            ->shouldBeCalledTimes(1);

        $this->handle($command);
    }

    public function it_should_throw_a_error_if_handle_failed(
        MessageBusInterface $messageBus,
        CommandInterface $command
    ) {
        $messageBus
            ->dispatch(Argument::cetera())
            ->willThrow(HandlerFailedException::class)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(\Throwable::class)->during('handle', [$command]);
    }
}
