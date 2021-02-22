<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Home;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Home\HomeController;
use Twig;

class HomeControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(HomeController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }
}
