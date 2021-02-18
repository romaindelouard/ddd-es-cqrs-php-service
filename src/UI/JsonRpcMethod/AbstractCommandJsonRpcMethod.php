<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\ParamsValidator;
use Symfony\Component\Validator\Constraint;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

abstract class AbstractCommandJsonRpcMethod implements JsonRpcMethodInterface, CommandJsonRpcMethodInterface
{
    protected ?Constraint $constraint;
    protected ParamsValidator $validator;
    protected CommandInterface $command;
    private CommandBusInterface $commandBus;

    public function apply(array $parameters = null): array
    {
        if (isset($this->constraint)) {
            $this->validator->validateParameters($parameters, $this->constraint);
        }

        $this->commandBus->handle($this->command);

        return [];
    }

    public function setValidator(ParamsValidator $validator): void
    {
        $this->validator = $validator;
    }

    public function setCommandBus(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }
}
