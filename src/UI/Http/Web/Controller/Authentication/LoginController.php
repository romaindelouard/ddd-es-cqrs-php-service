<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LoginController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/login",
     *     name="login",
     *     methods={"GET", "POST"}
     * )
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        return $this->render('login/login.html.twig', [
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ]);
    }
}
