<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Event;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

class EventCollectorListener implements EventListener
{
    private array $publishedEvents = [];

    public function handle(DomainMessage $domainMessage): void
    {
        $this->publishedEvents[] = $domainMessage;
    }
}
