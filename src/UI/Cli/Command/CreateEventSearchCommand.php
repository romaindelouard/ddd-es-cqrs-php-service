<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\UI\Cli\Command;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\Event\CreateEventSearch\CreateEventSearchCommand as CreateSearchEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventSearchCommand extends Command
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
          ->setName('app:create-event-search')
          ->setDescription('Create an event store on elasticsearch.');
    }

    protected function execute(
      InputInterface $input,
      OutputInterface $output
    ): int {
        $this->commandBus->handle(new CreateSearchEvent());

        $output->writeln('<info>Event search was created!</info>');

        return 0;
    }
}
