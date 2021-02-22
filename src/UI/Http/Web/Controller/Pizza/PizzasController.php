<?php

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Pizza;

use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PizzasController extends AbstractRenderController
{
    /**
     * @Route(
     *     path="/pizzas",
     *     name="pizzas",
     *     methods={"GET"}
     * )
     */
    public function get(Request $request): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $result = $this->ask(new GetPizzasQuery($page, $limit));

        return $this->render('pizza/index.html.twig', ['pizzas' => $result->data]);
    }
}
