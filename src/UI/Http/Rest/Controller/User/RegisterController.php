<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractCommandController
{
    /**
     * @Route(
     *     "/register",
     *     name="user_create",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="User created successfully"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @OA\RequestBody(
     *     @OA\Schema(type="object"),
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="uuid", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="password", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="User")
     *
     * @throws AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $uuid = $request->get('uuid');
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($uuid, "Uuid can\'t be null");
        Assertion::notNull($email, "Email can\'t be null");
        Assertion::notNull($plainPassword, "Password can\'t be null");

        $commandRequest = new SignUpCommand($uuid, $email, $plainPassword);

        $this->handle($commandRequest);

        return OpenApi::created("/user/$email");
    }
}
