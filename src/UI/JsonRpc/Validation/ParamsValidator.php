<?php

namespace Romaind\PizzaStore\UI\JsonRpc\Validation;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInvalidParamsException;

class ParamsValidator
{
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(ValidatorInterface $validator, LoggerInterface $logger = null)
    {
        $this->validator = $validator;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Validate params against a constraint
     * Throw appropriate JSON-RPC exception in case of errors.
     *
     * @param mixed $params
     * @param Constraint|Constraint[] $constraint
     *
     * @throws \Exception
     * @throws JsonRpcInvalidParamsException
     */
    public function validateParameters($params, $constraint, array $groups = null): void
    {
        $errorList = $this->validator->validate($params, $constraint, $groups);

        if (count($errorList) > 0) {
            $errorsMessage = [];
            foreach ($errorList as $error) {
                array_push($errorsMessage, $error->getMessage());
            }

            $this->logger->error(implode(PHP_EOL, $errorsMessage), $params);

            throw new JsonRpcInvalidParamsException($errorsMessage);
        }
    }
}
