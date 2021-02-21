<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Bus\AsyncEvent;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\AsyncEvent\AsyncEventHandlerInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\AsyncEvent\MessengerAsyncEventBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerAsyncEventBusSpec extends ObjectBehavior
{
    public function let(MessageBusInterface $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessengerAsyncEventBus::class);
        $this->shouldImplement(AsyncEventHandlerInterface::class);
    }

    public function it_should_handle_a_command(
        MessageBusInterface $messageBus
    ) {
        $command = DomainMessageTest::create();
        $messageBus
            ->dispatch(Argument::cetera())
            ->willReturn(new Envelope(new \stdClass()))
            ->shouldBeCalledTimes(1);

        $this->handle($command);
    }

    public function it_should_throw_a_error_if_handle_failed(
        MessageBusInterface $messageBus
    ) {
        $command = DomainMessageTest::create();
        $messageBus
            ->dispatch(Argument::cetera())
            ->willThrow(HandlerFailedException::class)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(\Throwable::class)->during('handle', [$command]);
    }
}
class DomainMessageTest
{
    public static function create()
    {
        $id = 'VIP Id';
        $payload = new SomeEvent();
        $playhead = 15;
        $metadata = new Metadata(['meta']);

        return DomainMessage::recordNow($id, $playhead, $metadata, $payload);
    }
}

class SomeEvent
{
}
