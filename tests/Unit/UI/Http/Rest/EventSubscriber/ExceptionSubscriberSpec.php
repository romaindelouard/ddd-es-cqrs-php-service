<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\Http\Rest\EventSubscriber\ExceptionSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriberSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('test', []);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExceptionSubscriber::class);
        $this->shouldImplement(EventSubscriberInterface::class);
    }
}
