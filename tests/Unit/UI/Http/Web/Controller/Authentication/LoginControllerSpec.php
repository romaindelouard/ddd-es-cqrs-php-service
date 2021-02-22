<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Authentication\LoginController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig;

class LoginControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(LoginController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }

    public function it_should_login(
        Twig\Environment $template,
        AuthenticationUtils $authUtils,
        Response $response
    ) {
        $authUtils->getLastUsername()
            ->willReturn('toto')->shouldBeCalledTimes(1);
        $authUtils->getLastAuthenticationError()
            ->willReturn(null)->shouldBeCalledTimes(1);

        $template->render(Argument::cetera())->shouldBeCalledTimes(1);

        $response = $this->login($authUtils);
        $response->getStatusCode()->shouldBe(Response::HTTP_OK);
    }
}
