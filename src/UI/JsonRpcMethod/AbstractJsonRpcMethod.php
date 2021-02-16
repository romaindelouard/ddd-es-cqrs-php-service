<?php

namespace Romaind\PizzaStore\UI\JsonRpcMethod;

use Composer\Semver\Constraint\Constraint;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

abstract class AbstractJsonRpcMethod implements JsonRpcMethodInterface
{
    protected Constraint $constraint;

    public function execute(array $params): ?array
    {
        if (isset($this->sanitizer)) {
            $this->sanitizer->sanitize($params);
        }

        if (isset($this->constraint)) {
            $this->validator->validate($params, $this->constraint);
        }

        return $this->apply($params);
    }
}
