<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method;

use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\CommandInterface;
use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;
use Symfony\Component\Validator\Constraint;

abstract class AbstractCommandJsonRpcMethod implements JsonRpcMethodInterface, CommandJsonRpcMethodInterface, WithParamsValidatorInterface
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
