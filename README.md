# DDD ES CQRS PHP service

[![codecov](https://codecov.io/gh/romaindelouard/ddd-es-cqrs-php-service/branch/master/graph/badge.svg?token=PJ7P1ON7UH)](https://codecov.io/gh/romaindelouard/ddd-es-cqrs-php-service)
[![Test suite](https://github.com/romaindelouard/ddd-es-cqrs-php-service/workflows/Test%20Suite/badge.svg?branch=master)](https://github.com/romaindelouard/ddd-es-cqrs-php-service/actions/workflows/test-suite.yml?query=branch%3Amaster)
[![Maintainability](https://api.codeclimate.com/v1/badges/040180137dbcbdcb0d5f/maintainability)](https://codeclimate.com/github/romaindelouard/ddd-es-cqrs-php-service/maintainability)

## Stack

- Nginx 1.17.9
- PHP 7.4
- RabbitMQ 3
- PostgreSQL 13
- PgAdmin 4
- Symfony 5.2
- ElasticSearch 7.10.2
- Kibana 7.10.2
- PgAdmin 4

## Project & environment setup

### Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone the project: `git clone https://github.com/romaindelouard/ddd-es-cqrs-php-service`
3. Move to the project folder: `cd ddd-es-cqrs-php-service`

### Environment configuration

1. Create a local environment file (`cp .env .env.local`) if you want to modify any parameter

### Application execution

1. Install all the dependencies and bring up the project with Docker executing: `make up`
2. Then you'll have all this services:
   - App (Nginx -> PHP-FPM -> Symfony): http://localhost:8080/
   - RabbitMQ (user:guest/password:guest): http://localhost:15672/
   - PostgreSQL (user:pgadmin4@pgadmin.org/password:admin): http://localhost:5050/
   - ElasticSearch: http://localhost:9100/
   - Kibana: http://localhost:5601/

### All Makefile commands

| Action                   | Command                |
| ------------------------ | ---------------------- |
| Shell                    | `make dev`             |
| Start                    | `make up`              |
| Run tests                | `make test`            |
| Run unit tests           | `make test-unit`       |
| Run functional tests     | `make test-functional` |
| Run the linter           | `make lint`            |
| Run static analysis tool | `make test-static`     |
| Check application layers | `make test-layer`      |

### Generate JWT keys

```bash
ssh-keygen -t rsa -b 4096 -m PEM -f config/jwt/jwtRS256.key
# Don't add passphrase
openssl rsa -in config/jwt/jwtRS256.key -pubout -outform PEM -out config/jwt/jwtRS256.key.pub
```

## Clean architecture :

![100-explicit-architecture-svg](https://user-images.githubusercontent.com/181649/107484965-6e478200-6b83-11eb-833c-fda0492680f6.png)

`make test-layer` is used to check application layers.

This command use deptrac with a configuration in the file [depfile.yaml](depfile.yaml).

### Command BUS

- [CommandBusInterface](src/Application/Command/CommandBusInterface.php) is an interface to implemented.
- [MessengerCommandBus](src/Infrastructure/Shared/Bus/Command/MessengerCommandBus.php) using the symfony message command bus

You can to create a command and his handler.

- [CommandInterface](src/Application/Command/CommandInterface.php)
- [CommandHandlerInterface](src/Application/Command/CommandHandlerInterface.php)

### Query BUS

- [CommandBusInterface](src/Application/Query/QueryBusInterface.php) is an interface to implemented.
- [MessengerQueryBus](src/Infrastructure/Shared/Bus/Query/MessengerQueryBus.php) using the symfony message query bus

You can to create a query and his handler.

- [QueryInterface](src/Application/Query/QueryInterface.php)
- [QueryHandlerInterface](src/Application/Query/QueryHandlerInterface.php)

### Asynchronous event BUS

[MessengerAsyncEventBus](src/Infrastructure/Shared/Bus/AsyncEvent/MessengerAsyncEventBus.php) using the symfony message async event bus

- [AsyncEventPublisher](src/Infrastructure/Shared/Event/Publisher/AsyncEventPublisher.php) publishes each event message.
- [SendEventsToElasticConsumer](src/Infrastructure/Shared/Event/Consumer/SendEventsToElasticConsumer.php) consumes each event message.

# Developer tooltip

## Unit tests with phpspec

You can run the application unit tests: `make test-unit`

You can generate a test unit class.

```
XDEBUG_MODE=coverage bin/phpspec describe Romaind/PizzaStore/Domain/Model/User/User
```

## Functional tests with behat

You can run the application unit tests: `make test-functional`

## GitHub Actions workflow

GitHub Actions workflow are defined here: https://docs.github.com/en/actions/quickstart

This project uses GitHub workflow to execute the unit and functional tests.

https://github.com/romaindelouard/ddd-es-cqrs-php-service/blob/master/.github/workflows/push.yml

## GrumPHP, each commit is tested

You can use a manual command to execute the GrumPHP tasks.

```
bin/grumphp run
GrumPHP is sniffing your code!

Running tasks with priority 0!
==============================

Running task 1/3: phpcsfixer... ✔
Running task 2/3: phpversion... ✔
Running task 3/3: yamllint... ✔
```
