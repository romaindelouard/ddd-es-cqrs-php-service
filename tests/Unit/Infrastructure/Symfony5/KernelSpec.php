<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Symfony5;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Symfony5\Kernel;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class KernelSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('test', true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Kernel::class);
        $this->shouldBeAnInstanceOf(BaseKernel::class);
    }
}
