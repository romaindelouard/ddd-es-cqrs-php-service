<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Event\Consumer;

use Broadway\Domain\DomainMessage;
use Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class SendEventsToElasticConsumer implements MessageSubscriberInterface
{
    private ElasticSearchEventRepository $eventElasticRepository;

    public function __construct(ElasticSearchEventRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    public function __invoke(DomainMessage $event): void
    {
        $this->eventElasticRepository->store($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield DomainMessage::class => [
            'from_transport' => 'events',
            'bus' => 'messenger.bus.event.async',
        ];
    }
}
