APP_ENV?=dev
APP_NAME:=pizza-service

# Default targets

## This target is used to install or compile an application without its configuration
.PHONY: build
build:
ifeq ($(APP_ENV),prod)
	composer run-script build-no-dev
else ifeq ($(APP_ENV),test)
	composer run-script build-test
else
	composer run-script build
endif

## This target should be used if you need to run some commands before to run the application
.PHONY: init
init: db-create db-migrate

.PHONY: build-all
build-all: build db-create init

.PHONY: start
start: APP_ENV:=dev
start:
	symfony serve

## This target is used to run all tests define for an application
.PHONY: test
test: test-unit test-functional test-static

## This target is used to run the application functional tests
.PHONY: test-functional
test-functional: APP_ENV:=test
test-functional:
	XDEBUG_MODE=coverage composer run-script test-functional

## This target is used to check application layers
.PHONY: test-layer
test-layer:
	composer run-script test-layer

## This target is used to run the application unit tests
.PHONY: test-unit
test-unit: APP_ENV:=test
test-unit:
	XDEBUG_MODE=coverage composer run-script test-unit

## This target is used to run the application static analysis tool
.PHONY: test-static
test-static:
	composer run-script test-static

## This target should return the application readiness probe status
.PHONY: healthz
healthz:
	composer run-script healthz

## This target is used to run the linter on the application
.PHONY: lint
lint:
	composer run-script lint

# Database targets

## This target is used to create a database with its schema.
.PHONY: db-create
db-create:
	composer run-script db-create

## This target is used to drop a database.
.PHONY: db-drop
db-drop:
	composer run-script db-drop

## This target is used to look at new migrations and apply them.
.PHONY: db-migrate
db-migrate:
	composer run-script db-migrate

# Docker targets

## This target run the application container including its dependencies and open a bash session to develop it.
.PHONY: dev
dev: docker-build
	docker-compose run --service-ports --rm $(APP_NAME) bash

## This target start the application and dependencies defined in your docker-compose.yml.
.PHONY: up
up: docker-build
	docker-compose up --remove-orphans

## This target stop the application and dependencies defined in your docker-compose.yml.
.PHONY: down
down:
	docker-compose down --remove-orphans

## This target build the application container including its dependencies and create docker volumes
.PHONY: docker-build
docker-build: docker-volumes-create
	DOCKER_BUILDKIT=1 docker build --build-arg=APP_ENV=$(APP_ENV) --target dev -t $(APP_NAME):dev .

# Custom targets

## This target create all the docker volumes
.PHONY: docker-volumes-create
docker-volumes-create:
	docker volume create --name=dev-postgres-data
	docker volume create --name=dev-rabbitmq-data
	docker volume create --name=dev-elasticsearch-data

## This target remove all the docker volumes
.PHONY: docker-volumes-remove
docker-volumes-remove:
	docker volume rm -f dev-postgres-data
	docker volume rm -f dev-rabbitmq-data
	docker volume rm -f dev-elasticsearch-data

## This target clean all docker containers
.PHONY: docker-clean
docker-clean:
	docker rm `docker ps -a -q -f label=com.docker.compose.project=$(APP_NAME)`

## This target kill all docker containers
.PHONY: docker-kill
docker-kill:
	docker kill `docker ps -a -q -f label=com.docker.compose.project=$(APP_NAME)`
