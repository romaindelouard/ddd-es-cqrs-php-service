<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Authentication\RegisterController;
use Twig;

class RegisterControllerSpec extends ObjectBehavior
{
    public function let(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus
    ) {
        $this->beConstructedWith($template, $commandBus, $queryBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RegisterController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }
}
