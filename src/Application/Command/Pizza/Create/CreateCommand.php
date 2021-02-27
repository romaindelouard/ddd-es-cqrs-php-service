<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Command\Pizza\Create;

use Romaind\PizzaStore\Application\Command\CommandInterface;

class CreateCommand implements CommandInterface
{
    public string $uuid;
    public string $name;
    public ?string $description;

    public function __construct(string $uuid, string $name, ?string $description)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
    }
}
