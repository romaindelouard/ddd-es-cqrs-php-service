<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\Http\Rest\EventSubscriber\JsonBodyParserSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JsonBodyParserSubscriberSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(JsonBodyParserSubscriber::class);
        $this->shouldImplement(EventSubscriberInterface::class);
    }
}
