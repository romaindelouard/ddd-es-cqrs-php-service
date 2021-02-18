<?php

namespace Tests\Unit\Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\JsonRpcMethod\CreatePizza\CreatePizzaMethod;

class CreatePizzaMethodSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CreatePizzaMethod::class);
    }
}
