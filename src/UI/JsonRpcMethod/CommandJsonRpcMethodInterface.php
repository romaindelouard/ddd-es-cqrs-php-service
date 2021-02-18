<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;

interface CommandJsonRpcMethodInterface
{
    public function setCommandBus(CommandBusInterface $commandBus): void;
}
