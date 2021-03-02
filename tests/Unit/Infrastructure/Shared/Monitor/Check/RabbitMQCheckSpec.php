<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check;

use Laminas\Diagnostics\Check\AbstractCheck;
use Laminas\Diagnostics\Result\Success;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check\RabbitMQCheck;

class RabbitMQCheckSpec extends ObjectBehavior
{
    public function let(AMQPLazyConnection $connection)
    {
        $this->beConstructedWith($connection);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RabbitMQCheck::class);
        $this->shouldHaveType(AbstractCheck::class);
    }

    public function it_should_check_with_success(
        AMQPLazyConnection $connection
    ) {
        $connection->getIO()->shouldBeCalledTimes(1);
        $connection->channel()->shouldBeCalledTimes(1);

        $result = $this->check();
        $result->shouldHaveType(Success::class);
    }
}
