<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Pizza;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Pizza\PizzasController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig;

class PizzasControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType(PizzasController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }

    public function it_should_get_pizza_list(
        Twig\Environment $template,
        QueryBusInterface $queryBus,
        Request $request,
        Collection $collection
    ) {
        $limit = 3;
        $page = 11;
        $request->get('page', 1)->willReturn($page)->shouldBeCalledTimes(1);
        $request->get('limit', 10)->willReturn($limit)->shouldBeCalledTimes(1);

        $collection->data = [];
        $collection->limit = $limit;
        $collection->page = $page;
        $collection->total = 123;

        $queryBus->ask(Argument::type(GetPizzasQuery::class))
            ->willReturn($collection)->shouldBeCalledTimes(1);

        $template->render(Argument::cetera())->shouldBeCalledTimes(1);
        $response = $this->get($request);
        $response->getStatusCode()->shouldBe(Response::HTTP_OK);
    }
}
