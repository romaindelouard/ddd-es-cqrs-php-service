<?php

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Pizza;

use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetPizzasController extends AbstractRenderController
{
    /**
     * @Route(
     *     path="/pizzas{_format?}",
     *     name="pizzas",
     *     methods={"GET"},
     *     requirements={"_format"="json|html"}
     * )
     */
    public function __invoke(Request $request): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $query = new GetPizzasQuery($page, $limit);

        $result = $this->ask($query);

        return $this->render('pizza/index.html.twig', ['pizzas' => $result->data]);
    }
}
