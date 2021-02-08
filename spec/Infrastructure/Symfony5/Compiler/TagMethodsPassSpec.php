<?php

namespace spec\Romaind\PizzaStore\Infrastructure\Symfony5\Compiler;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Symfony5\Compiler\TagMethodsPass;

class TagMethodsPassSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagMethodsPass::class);
    }
}
