<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Event\Consumer;

use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Event\Consumer\SendEventsToElasticConsumer;
use Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class SendEventsToElasticConsumerSpec extends ObjectBehavior
{
    public function let(ElasticSearchEventRepository $eventElasticRepository)
    {
        $this->beConstructedWith($eventElasticRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SendEventsToElasticConsumer::class);
        $this->shouldImplement(MessageSubscriberInterface::class);
    }

    public function it_should_store_a_message(
        ElasticSearchEventRepository $eventElasticRepository
    ) {
        $event = DomainMessageTest::create();
        $eventElasticRepository->store($event)->shouldBeCalledTimes(1);

        $this->__invoke($event);
    }

    public function it_should_get_handled_messages()
    {
        $messages = $this::getHandledMessages();

        $messages->shouldBeAnInstanceOf('Iterator');
        $messages->current()->shouldBe([
            'from_transport' => 'events',
            'bus' => 'messenger.bus.event.async',
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
