<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Infrastructure\Shared\Monitor\Check;

use Laminas\Diagnostics\Check\CheckCollectionInterface;
use PhpAmqpLib\Connection\AMQPLazyConnection;

class RabbitMQCollection implements CheckCollectionInterface
{
    private array $checks = [];

    public function __construct(array $rabbitMqMonitorConfig)
    {
        foreach ($rabbitMqMonitorConfig as $name => $config) {
            if (isset($config['dsn'])) {
                $config = array_merge($config, parse_url($config['dsn']));
                if (isset($config['pass'])) {
                    $config['password'] = $config['pass'];
                    // Cleanup
                    unset($config['pass']);
                }
                if (isset($config['path'])) {
                    $config['vhost'] = urldecode(substr($config['path'], 1));
                    // Cleanup
                    unset($config['path']);
                }
            }
            $connection = new AMQPLazyConnection(
                $config['host'],
                $config['port'],
                $config['user'],
                $config['password'],
                $config['vhost']
            );
            $check = new RabbitMQCheck($connection);
            $check->setLabel(sprintf('Rabbit MQ "%s"', $name));

            $this->checks[sprintf('rabbit_mq_%s', $name)] = $check;
        }
    }

    public function getChecks(): array
    {
        return $this->checks;
    }
}
