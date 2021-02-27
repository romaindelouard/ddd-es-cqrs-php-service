<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Event;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventHandling\EventListener;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Event\EventCollectorListener;

class EventCollectorListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EventCollectorListener::class);
        $this->shouldImplement(EventListener::class);
    }

    public function it_should_handle_a_message()
    {
        $message = DomainMessageTest::create();
        $this->handle($message);

        $this->getPublishedEvents()->shouldBe([$message]);
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
