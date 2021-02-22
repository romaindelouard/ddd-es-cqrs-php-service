<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\Pizza;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\Pizza\GetPizzasController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetPizzasControllerSpec extends ObjectBehavior
{
    public function let(
        QueryBusInterface $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->beConstructedWith($queryBus, $router);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetPizzasController::class);
        $this->shouldBeAnInstanceOf(AbstractQueryController::class);
    }

    public function it_should_get_a_pizza_list(
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

        $result = $this->__invoke($request);

        $result->getStatusCode()->shouldBe(OpenApi::HTTP_OK);
    }
}
