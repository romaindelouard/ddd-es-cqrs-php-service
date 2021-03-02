<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check\RabbitMQCollection;

class RabbitMQCollectionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([
            'default' => [
                'host' => 'localhost',
                'port' => 5672,
                'user' => 'guest',
                'password' => 'guest',
                'vhost' => '/',
            ],
            'other' => [
                'dsn' => 'amqp://guest:guest@localhost:5672/%2F'
            ]
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RabbitMQCollection::class);
    }

    public function it_should_create_rabbitmq_check_collection()
    {
        $check = $this->getChecks();
        $check->shouldHaveCount(2);
    }
}
