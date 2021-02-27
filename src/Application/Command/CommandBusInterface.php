<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): void;
}
