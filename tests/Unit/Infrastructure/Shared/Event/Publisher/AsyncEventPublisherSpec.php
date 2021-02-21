<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Event\Publisher;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventHandling\EventListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\AsyncEvent\MessengerAsyncEventBus;
use Romaind\PizzaStore\Infrastructure\Shared\Event\Publisher\AsyncEventPublisher;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AsyncEventPublisherSpec extends ObjectBehavior
{
    public function let(MessengerAsyncEventBus $bus)
    {
        $this->beConstructedWith($bus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AsyncEventPublisher::class);
        $this->shouldImplement(EventSubscriberInterface::class);
        $this->shouldImplement(EventListener::class);
    }

    public function it_should_publish_a_message(MessengerAsyncEventBus $bus)
    {
        $message = DomainMessageTest::create();

        $this->handle($message);
        $bus->handle($message)->shouldBeCalledTimes(1);

        $this->publish();
    }

    public function it_should_not_publish_if_no_message(MessengerAsyncEventBus $bus)
    {
        $bus->handle(Argument::any())->shouldNotBeCalled();

        $this->publish();
    }

    public function it_should_subscribe_events()
    {
        $this->getSubscribedEvents()->shouldBe([
            KernelEvents::TERMINATE => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ]);
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
