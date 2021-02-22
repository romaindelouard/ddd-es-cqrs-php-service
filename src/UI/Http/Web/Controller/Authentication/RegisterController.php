<?php

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegisterController extends AbstractRenderController
{
    private const REGISTER_FORM_TEMPLATE_PATH = 'register/index.html.twig';

    /**
     * @Route(
     *     "/register",
     *     name="register",
     *     methods={"GET"}
     * )
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getAction(): Response
    {
        return $this->render(self::REGISTER_FORM_TEMPLATE_PATH);
    }

    /**
     * @Route(
     *     "/register",
     *     name="register-post",
     *     methods={"POST"}
     * )
     *
     * @throws AssertionFailedException
     * @throws \Throwable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function post(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $uuid = Uuid::uuid4()->toString();

        try {
            Assertion::notNull($email, 'Email can\'t be null');
            Assertion::notNull($password, 'Password can\'t be null');

            $this->handle(new SignUpCommand($uuid, $email, $password));

            return $this->render('register/user_created.html.twig', ['uuid' => $uuid, 'email' => $email]);
        } catch (EmailAlreadyExistException $exception) {
            return $this->render(
                self::REGISTER_FORM_TEMPLATE_PATH,
                ['error' => 'Email already exists.'],
                Response::HTTP_CONFLICT
            );
        } catch (\InvalidArgumentException $exception) {
            return $this->render(
                self::REGISTER_FORM_TEMPLATE_PATH,
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
