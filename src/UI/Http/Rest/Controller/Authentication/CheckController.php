<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\Authentication;

use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInCommand;
use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenQuery;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CheckController extends AbstractCommandQueryController
{
    /**
     * @Route(
     *     "/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "_username": "\w+",
     *      "_password": "\w+"
     *     }
     * )
     * @OA\Response(
     *     response=200,
     *     description="Login success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *          property="token", type="string"
     *        )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Bad credentials"
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="_password", type="string"),
     *         @OA\Property(property="_username", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="Authentication")
     *
     * @throws AssertionFailedException
     * @throws InvalidCredentialsException
     * @throws \Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $username = $request->get('_username');
        Assertion::notNull($username, 'Username cant\'t be empty');
        $signInCommand = new SignInCommand(
            $username,
            $request->get('_password')
        );

        $this->handle($signInCommand);

        return OpenApi::fromPayload(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ],
            OpenApi::HTTP_OK
        );
    }
}
