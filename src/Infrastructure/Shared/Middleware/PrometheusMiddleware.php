<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Middleware;

use Romaind\PizzaStore\Infrastructure\Shared\Monitoring\PrometheusMonitor;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class PrometheusMiddleware implements MiddlewareInterface
{
    private PrometheusMonitor $prometheusMonitor;

    public function __construct(PrometheusMonitor $prometheusMonitor)
    {
        $this->prometheusMonitor = $prometheusMonitor;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->registerCounter($envelope);

        return $stack->next()->handle($envelope, $stack);
    }

    public function registerCounter(Envelope $envelope): void
    {
        $stamp = $envelope->last(BusNameStamp::class);
        if (!$stamp instanceof BusNameStamp) {
            return;
        }
        $busName = str_replace('.', '_', $stamp->getBusName());
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp instanceof HandledStamp) {
            return;
        }

        $counter = $this->prometheusMonitor->collectorRegistry()->getOrRegisterCounter(
            $busName,
            'metricName',
            'Executed message',
            ['message', 'label']
        );
        $values = [
            $handledStamp->getHandlerName(),
            substr((string) strrchr($handledStamp->getHandlerName(), '\\'), 1),
        ];
        $counter->inc($values);
    }
}
