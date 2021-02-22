<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\User\ProfileController;
use Symfony\Component\HttpFoundation\Response;
use Twig;

class ProfileControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(ProfileController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }

    public function it_should_go_to_profile(
        Twig\Environment $template
    ) {
        $template->render(Argument::cetera())->shouldBeCalledTimes(1);
        $response = $this->get();
        $response->getStatusCode()->shouldBe(Response::HTTP_OK);
    }
}
