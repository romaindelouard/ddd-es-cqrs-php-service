<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Home;

use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/",
     *     name="home",
     *     methods={"GET"}
     * )
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function get(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
