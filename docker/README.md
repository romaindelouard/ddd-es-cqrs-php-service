## Started the dockerization

```bash
# Create a new network
docker network create dev

# Create the docker volumes
docker volume create --name=dev-postgres-data

# Build docker containers and run with a bash command (without apache2 service running)
make dev

# Build docker containers and run apache2 service
make up

# Visualize IP address
docker inspect --format '{{ .NetworkSettings.Networks.dev.IPAddress }}' $(docker ps -f name=pizza-service -q)

# Launch tests
docker-compose exec pizza-service make test

# stop the containers
docker kill $(docker ps -q)
docker rm $(docker ps -a -q)

# Clean all docker containers and volumes
docker kill $(docker ps -q) && docker rm $(docker ps -a -q) && docker rmi $(docker images -q)
docker volume prune
```

## Useful commands

```bash
# bash commands
$ docker-compose exec pizza-service bash
$ docker-compose exec postgres bash

# Composer (e.g. composer update)
$ docker-compose exec pizza-service composer update

# SF commands (Tips: there is an alias inside php container)
$ docker-compose exec pizza-service php /var/www/symfony/bin/console cache:clear
# Same command by using alias
$ docker-compose exec pizza-service bash

# Retrieve an IP Address (here for the nginx container)
$ docker inspect --format '{{ .NetworkSettings.Networks.dev.IPAddress }}' $(docker ps -f name=nginx -q)
$ docker inspect $(docker ps -f name=nginx -q) | grep IPAddress

# Connect to the pizza database
docker exec -it postgres psql -U postgres pizza

# Rabbitmq administration
Connect to http://127.0.0.1:15672/ with lafourchette/lafourchette
docker-compose exec pizza-service composer rabbitmq

# Check CPU consumption
$ docker stats $(docker inspect -f "{{ .Name }}" $(docker ps -q))

# stop all containers:
$ docker kill $(docker ps -q)

# remove all containers
$ docker rm $(docker ps -a -q)

# remove all docker images
$ docker rmi $(docker images -q)
```

## Update composer.lock

```bash
# setup php 7.4 on ubuntu desktop
sudo apt-add-repository ppa:ondrej/php
sudo apt install php7.4-cli php7.4-curl php7.4-mbstring php7.4-xml php7.4-pgsql
sudo apt install php7.4-xdebug
sudo apt install php7.4-intl
sudo apt install php7.4-amqp
sudo apt install php7.4-dev
sudo apt install php-pear
sudo  pecl install swoole
sudo sh -c "echo 'extension=swoole.so' > /etc/php/7.4/cli/conf.d/20-swoole.ini"

# clean vendor directory and composer.lock file
rm composer.lock
rm -rf vendor

# setup composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'c5b9b6d368201a9db6f74e2611495f369991b72d9c8cbd3ffbc63edff210eb73d46ffbfce88669ad33695ef77dc76976') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# run composer update
php -d memory_limit=-1 composer.phar update --no-scripts --ansi -vvv --no-interaction --optimize-autoloader --profile

# run docker container
make dev
$ make healthz
$ make test
$ exit
make up
curl "http://localhost:3000/doc.json"
curl -X POST \
  http://127.0.0.1:3000/json-rpc \
  -H 'content-type: application/json' \
  -d '{
	"jsonrpc": "2.0", 
	"method": "getHello", 
	"params": {}, 
	"id": "3"
}
'
```
