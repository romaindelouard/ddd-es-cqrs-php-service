<?php

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenQuery;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfileController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/profile",
     *     name="profile",
     *     methods={"GET"}
     * )
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function profile(): Response
    {
        $user = $this->getUser();
        $username = $user->getUsername();
        $token = $this->ask(new GetTokenQuery($username));

        return $this->render('profile/index.html.twig', ['token' => $token]);
    }
}
