<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\Http\Rest\EventSubscriber\ExceptionSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

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

    public function it_should_subscribe_events()
    {
        $this->getSubscribedEvents()->shouldBe([
            KernelEvents::EXCEPTION => 'onKernelException',
        ]);
    }
}
