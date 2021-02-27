<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Command\Event\CreateEventSearch;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Application\Command\Event\CreateEventSearch\CreateEventSearchCommand;

class CreateEventSearchCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateEventSearchCommand::class);
        $this->shouldImplement(CommandInterface::class);
    }
}
