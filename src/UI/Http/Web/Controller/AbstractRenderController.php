<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Web\Controller;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Query\Collection;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig;

abstract class AbstractRenderController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    protected Twig\Environment $template;

    public function __construct(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus
    ) {
        $this->template = $template;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    /**
     * @throws \Throwable
     */
    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

    /**
     * @return Item|Collection|mixed
     *
     * @throws \Throwable
     */
    protected function ask(QueryInterface $query)
    {
        return $this->queryBus->ask($query);
    }

    protected function render(
        string $view,
        array $parameters = [],
        int $code = Response::HTTP_OK
    ): Response {
        $content = $this->template->render($view, $parameters);

        return new Response($content, $code);
    }
}
