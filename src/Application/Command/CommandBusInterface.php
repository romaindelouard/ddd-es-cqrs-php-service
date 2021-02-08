<?php

namespace Romaind\PizzaStore\Application\Command;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): void;
}
