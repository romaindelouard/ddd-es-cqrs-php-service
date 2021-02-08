<?php

namespace spec\Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Collector;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Collector\MappingCollector;

class MappingCollectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MappingCollector::class);
    }
}
