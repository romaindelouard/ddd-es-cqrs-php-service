<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Romaind\PizzaStore\Domain\Model\User\Exception\ForbiddenException;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandController;
use Romaind\PizzaStore\UI\Http\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserChangeEmailController extends AbstractCommandController
{
    private Session $session;

    public function __construct(
        Session $session,
        CommandBusInterface $commandBus
    ) {
        parent::__construct($commandBus);
        $this->session = $session;
    }

    /**
     * @Route(
     *     "/users/{uuid}/email",
     *     name="user_change_email",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=204,
     *     description="Email changed"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @throws AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $this->validateUuid($uuid);

        $email = $request->get('email');

        Assertion::notNull($email, "Email can\'t be null");

        $command = new ChangeEmailCommand($uuid, $email);

        $this->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function validateUuid(string $uuid): void
    {
        if (!$this->session->get()->uuid()->equals(Uuid::fromString($uuid))) {
            throw new ForbiddenException();
        }
    }
}
