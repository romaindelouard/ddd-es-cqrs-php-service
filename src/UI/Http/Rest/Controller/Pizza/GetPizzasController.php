<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\Pizza;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetPizzasController extends AbstractQueryController
{
    /**
     * @Route(
     *     path="/pizzas",
     *     name="pizzas",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return a pizza list",
     *     ref="#/components/responses/pizzas"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request",
     *     @OA\JsonContent(ref="#/components/schemas/Error")
     *
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\Parameter(ref="#/components/parameters/page")
     * @OA\Parameter(ref="#/components/parameters/limit")
     *
     * @OA\Tag(name="Pizzas")
     *
     * @Security(name="Bearer")
     *
     * @throws AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(Request $request): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        Assertion::numeric($page, 'Page number must be an integer');
        Assertion::numeric($limit, 'Limit results must be an integer');

        $query = new GetPizzasQuery((int) $page, (int) $limit);

        $response = $this->ask($query);

        return $this->jsonCollection($response, 200, true);
    }
}
