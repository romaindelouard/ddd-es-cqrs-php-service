<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Method;

use Romaind\PizzaStore\UI\JsonRpc\Validation\ParamsValidator;

interface WithParamsValidatorInterface
{
    public function setValidator(ParamsValidator $validator): void;
}
