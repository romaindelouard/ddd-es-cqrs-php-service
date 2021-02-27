<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Bus\Query;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Query\MessengerQueryBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBusSpec extends ObjectBehavior
{
    public function let(MessageBusInterface $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessengerQueryBus::class);
        $this->shouldImplement(QueryBusInterface::class);
    }

    public function it_should_ask_a_query(
        MessageBusInterface $messageBus,
        QueryInterface $query
    ) {
        $stamp = new HandledStamp(['result'], 'handlerName');
        $messageBus->dispatch($query)
            ->willReturn(new Envelope(new \stdClass(), [$stamp]))
            ->shouldBeCalledTimes(1);

        $this->ask($query)->shouldBe(['result']);
    }

    public function it_should_throw_a_error_if_ask_failed(
        MessageBusInterface $messageBus,
        QueryInterface $query
    ) {
        $messageBus
            ->dispatch($query)
            ->willThrow(HandlerFailedException::class)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(\Throwable::class)->during('ask', [$query]);
    }
}
