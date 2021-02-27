<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\Pizza;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Romaind\PizzaStore\Application\Query\Pizza\GetPizzas\GetPizzasQuery;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetPizzasController extends AbstractQueryController
{
    /**
     * @Route(
     *     path="/pizzas",
     *     name="getPizzas",
     *     methods={"GET"}
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the user of the given email",
     *     @OA\JsonContent(
     *        type="object"
     *      )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     *
     * )
     *
     * @OA\Tag(name="Pizzas")
     *
     * @Security(name="Bearer")
     *
     * @throws AssertionFailedException
     */
    public function get(Request $request): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        Assertion::numeric($page, 'Page number must be an integer');
        Assertion::numeric($limit, 'Limit results must be an integer');

        $query = new GetPizzasQuery((int) $page, (int) $limit);

        $response = $this->ask($query);

        return $this->jsonCollection($response, OpenApi::HTTP_OK, true);
    }
}
