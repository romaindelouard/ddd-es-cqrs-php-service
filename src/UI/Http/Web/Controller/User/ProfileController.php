<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Web\Controller\User;

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
    public function get(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
