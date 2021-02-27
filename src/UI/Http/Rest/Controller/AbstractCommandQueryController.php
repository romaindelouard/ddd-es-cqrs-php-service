<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Http\Rest\Controller;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractCommandQueryController extends AbstractQueryController
{
    private CommandBusInterface $commandBus;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        UrlGeneratorInterface $router
    ) {
        parent::__construct($queryBus, $router);
        $this->commandBus = $commandBus;
    }

    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
