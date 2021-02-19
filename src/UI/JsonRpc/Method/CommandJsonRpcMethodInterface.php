<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;

interface CommandJsonRpcMethodInterface
{
    public function setCommandBus(CommandBusInterface $commandBus): void;
}
