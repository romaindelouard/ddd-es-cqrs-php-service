<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Romaind\PizzaStore\Application\Query\Collection as ResultCollection;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\Application\Query\QueryInterface;
use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\ParamsValidator;
use Symfony\Component\Validator\Constraint;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

abstract class AbstractQueryJsonRpcMethod implements JsonRpcMethodInterface, QueryJsonRpcMethodInterface
{
    protected ?Constraint $constraint;
    protected ParamsValidator $validator;
    protected QueryInterface $query;
    private QueryBusInterface $queryBus;

    public function apply(array $parameters = null): array
    {
        if (isset($this->constraint)) {
            $this->validator->validateParameters($parameters, $this->constraint);
        }

        $result = $this->queryBus->ask($this->query);

        return $this->parseResult($result);
    }

    abstract protected function parseResult(ResultCollection $collection): array;

    public function setValidator(ParamsValidator $validator)
    {
        $this->validator = $validator;
    }

    public function setQueryBus(QueryBusInterface $queryBus): void
    {
        $this->queryBus = $queryBus;
    }
}
