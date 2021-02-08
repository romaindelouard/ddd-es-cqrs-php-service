# DDD ES CQRS PHP service

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
2. Clone the project: `git clone https://github.com/romaindlf/ddd-es-cqris-php-service`
3. Move to the project folder: `cd ddd-es-cqris-php-service`

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

| Action               | Command                |
| -------------------- | ---------------------- |
| Shell                | `make dev`             |
| Start                | `make up`              |
| Run tests            | `make test`            |
| Run unit tests       | `make test-unit`       |
| Run functional tests | `make test-functional` |
| Run the linter       | `make lint`            |

### Generate JWT keys

```bash
ssh-keygen -t rsa -b 4096 -m PEM -f config/jwt/jwtRS256.key
# Don't add passphrase
openssl rsa -in config/jwt/jwtRS256.key -pubout -outform PEM -out config/jwt/jwtRS256.key.pub
```

## Clean architecture :

![100-explicit-architecture-svg](https://user-images.githubusercontent.com/181649/107484965-6e478200-6b83-11eb-833c-fda0492680f6.png)

### Command BUS

### Query BUS

### Event BUS

You can generate a test unit class:

```
XDEBUG_MODE=coverage bin/phpspec describe Romaind/PizzaStore/Domain/Model/User/User
```

GitHub Actions workflow: https://docs.github.com/en/actions/quickstart

```
bin/grumphp run
GrumPHP is sniffing your code!

Running tasks with priority 0!
==============================

Running task 1/3: phpcsfixer... ✔
Running task 2/3: phpversion... ✔
Running task 3/3: yamllint... ✔
```
