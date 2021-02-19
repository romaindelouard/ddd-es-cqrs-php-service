<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Romaind\PizzaStore\Infrastructure\Server\JsonRpcServer\Validation\ParamsValidator;

interface WithParamsValidatorInterface
{
    public function setValidator(ParamsValidator $validator): void;
}
