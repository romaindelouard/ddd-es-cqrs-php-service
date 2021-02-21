<?php

namespace Tests\Functional\Context;

use Broadway\Domain\DomainMessage;
use Romaind\PizzaStore\Infrastructure\Shared\Event\EventCollectorListener;

class EventCollectorListenerContext extends AbstractContext
{
    private function getEventCollectorListener(): EventCollectorListener
    {
        return $this->getContainer()->get(EventCollectorListener::class);
    }

    /**
     * @Then I expect event collector listener to collect an event
     * @Then I expect event collector listener to collect :count event
     * @Then I expect event collector listener to collect :count events
     */
    public function iExpectEventCollectorListenerToCollectAnEvent($count = 1)
    {
        $events = $this->getEventCollectorListener()->getPublishedEvents();
        $nbCollectedEvents = count($events);
        if ($nbCollectedEvents !== (int) $count) {
            throw new \Exception(sprintf('Event collector listener has collected %d events', $nbCollectedEvents));
        }
    }

    private function getCollectedEvents()
    {
        $events = $this->getEventCollectorListener()->getPublishedEvents();

        $collectedEvents = [];
        /** @var DomainMessage $event */
        foreach ($events as $event) {
            $eventType = get_class($event->getPayload());
            $collectedEvents[$eventType] = isset($collectedEvents[$eventType])
              ? ++$collectedEvents[$eventType]
              : 1;
        }

        return $collectedEvents;
    }

    /**
     * @Then I expect event collector listener to collect :count event type :eventClass
     */
    public function iExpectEventCollectorListenerToCollectAnEventType($count, $eventClass)
    {
        $events = $this->getCollectedEvents();
        if (!isset($events[$eventClass])) {
            throw new \Exception(sprintf('Event collector listener has not collected event type %s', $eventClass));
        }

        if ($events[$eventClass] !== (int) $count) {
            throw new \Exception(sprintf('Event collector listener has collected %d event(s) type %s', $events[$eventClass], $eventClass));
        }
    }
}
