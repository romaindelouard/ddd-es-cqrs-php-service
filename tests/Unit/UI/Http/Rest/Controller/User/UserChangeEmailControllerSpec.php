<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\User\UserChangeEmailController;
use Romaind\PizzaStore\UI\Http\Session;

class UserChangeEmailControllerSpec extends ObjectBehavior
{
    public function let(
        Session $session,
        CommandBusInterface $commandBus
    ) {
        $this->beConstructedWith($session, $commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserChangeEmailController::class);
        $this->shouldBeAnInstanceOf(AbstractCommandController::class);
    }
}
