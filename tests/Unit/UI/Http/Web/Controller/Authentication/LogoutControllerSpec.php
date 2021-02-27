<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Authentication\LogoutController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Twig;

class LogoutControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(LogoutController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }

    public function it_throws_an_authentication_exception()
    {
        $this->shouldThrow(AuthenticationException::class)->during('logout');
    }
}
