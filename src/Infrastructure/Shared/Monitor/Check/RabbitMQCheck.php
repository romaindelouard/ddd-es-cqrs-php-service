<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check;

use Laminas\Diagnostics\Check\AbstractCheck;
use Laminas\Diagnostics\Result\ResultInterface;
use Laminas\Diagnostics\Result\Success;
use PhpAmqpLib\Connection\AMQPLazyConnection;

class RabbitMQCheck extends AbstractCheck
{
    private AMQPLazyConnection $connection;

    public function __construct(AMQPLazyConnection $connection)
    {
        $this->connection = $connection;
    }

    public function check(): ResultInterface
    {
        $this->connection->getIO();
        $this->connection->channel();

        return new Success();
    }
}
