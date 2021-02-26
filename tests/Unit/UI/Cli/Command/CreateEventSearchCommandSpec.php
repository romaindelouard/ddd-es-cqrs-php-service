<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Cli\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\Event\CreateEventSearch\CreateEventSearchCommand as CreateEventSearch;
use Romaind\PizzaStore\UI\Cli\Command\CreateEventSearchCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventSearchCommandSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateEventSearchCommand::class);
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_run_and_create_a_event_search(
        CommandBusInterface $commandBus,
        InputInterface $input,
        OutputInterface $output
    ) {
        $commandBus->handle(Argument::type(CreateEventSearch::class))
            ->shouldBeCalledTimes(1);

        $this->run($input, $output)->shouldReturn(0);
    }
}
