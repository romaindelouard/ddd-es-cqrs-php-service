<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Monitoring;

use Prometheus\CollectorRegistry;

class PrometheusMonitor
{
    private CollectorRegistry $collectorRegistry;

    public function __construct(CollectorRegistry $collectorRegistry)
    {
        $this->collectorRegistry = $collectorRegistry;
    }

    public function collectorRegistry(): CollectorRegistry
    {
        return $this->collectorRegistry;
    }
}
