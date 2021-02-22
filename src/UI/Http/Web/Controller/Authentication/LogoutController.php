<?php

namespace Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LogoutController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/logout",
     *     name="logout"
     * )
     */
    public function logout(): void
    {
        throw new AuthenticationException('I shouldn\'t be here..');
    }
}
