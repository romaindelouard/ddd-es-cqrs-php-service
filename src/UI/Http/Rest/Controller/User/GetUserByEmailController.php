<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Application\Query\User\FindByEmail\FindByEmailQuery;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\Routing\Annotation\Route;

class GetUserByEmailController extends AbstractQueryController
{
    /**
     * @Route(
     *     "/users/{email}",
     *     name="find_user",
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
     * )
     * @OA\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @throws AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(string $email): OpenApi
    {
        Assertion::email($email, "Email can\'t be empty or invalid");

        $query = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($query);

        return $this->json($user);
    }
}
