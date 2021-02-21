<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Collector;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Collector\MappingCollector;
use Romaind\PizzaStore\UI\JsonRpc\Method\JsonRpcMethodInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodAwareInterface;

class MappingCollectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MappingCollector::class);
        $this->shouldImplement(JsonRpcMethodAwareInterface::class);
    }

    public function it_should_add_a_method(JsonRpcMethodInterface $method)
    {
        $this->addJsonRpcMethod('methodName', $method);
        $this->getMappingList()->shouldBe(['methodName' => $method]);
    }
}
