# syntax=docker/dockerfile:experimental

ARG PHP_VERSION="7.4"

FROM php:${PHP_VERSION}-fpm-alpine as php-base
# @see https://pecl.php.net/package/APCU
ARG APCU_VERSION="5.1.19"
# @see https://pecl.php.net/package/mcrypt
ARG MCRYPT_VERSION="1.0.4"

RUN apk add --no-cache autoconf automake bash gcc g++ git make unzip \
    && docker-php-source extract \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    # intl
    && apk add --no-cache icu-dev \
    && docker-php-ext-install intl \
    # amqp
    &&  docker-php-ext-install sockets \
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    # apcu
    && pecl install apcu-${APCU_VERSION} \
    && docker-php-ext-enable apcu \
    # mcrypt
    && apk add --no-cache libmcrypt-dev \
    && pecl install mcrypt-${MCRYPT_VERSION} \
    # curl
    && apk add --no-cache curl curl-dev libcurl \
    && ln -s /usr/lib/libcurl.so.4 /usr/lib/libcurl-gnutls.so.4 \
    && docker-php-ext-install curl \
    # xml
    && apk add --no-cache libxml2-dev \
    && docker-php-ext-install xml \
    # pgsql
    && apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    # mbstring
    && apk add --no-cache oniguruma-dev \
    && docker-php-ext-install mbstring \
    # xdebug
    && pecl install xdebug \
    && docker-php-ext-enable xdebug  \
    # others
    && docker-php-ext-install opcache \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets \
    && apk del .phpize-deps

FROM composer AS composer-bin

FROM php-base as app-base
ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV}

ENV COMPOSER_ALLOW_SUPERUSER="1"
COPY --chown=www-data:www-data --from=composer-bin /usr/bin/composer /usr/local/bin/composer

COPY ./docker/entrypoint.sh /
RUN chmod +x /entrypoint.sh && chown -R www-data:www-data /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
WORKDIR /var/www/html
USER www-data

FROM app-base as builder

# Don't execute composer scripts during composer intall
ENV COMPOSER_ARGS --no-scripts --no-progress --no-suggest --prefer-dist --no-interaction --no-autoloader
USER www-data
COPY --chown=www-data:www-data Makefile composer.* /var/www/html/
COPY --chown=www-data:www-data src/Infrastructure/Symfony5/Kernel.php /var/www/html/src/Infrastructure/Symfony5/Kernel.php
COPY --chown=www-data:www-data bin/console /var/www/html/bin/console
RUN make APP_ENV=${APP_ENV} PHP_VERSION=${PHP_VERSION} build

FROM builder as dev
USER root
# Blackfire setup
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && architecture=$(case $(uname -m) in i386 | i686 | x86) echo "i386" ;; x86_64 | amd64) echo "amd64" ;; aarch64 | arm64 | armv8) echo "arm64" ;; *) echo "amd64" ;; esac) \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/$architecture/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

RUN find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/access.log = \/dev\/null/access.log = \/proc\/self\/fd\/1/g"

USER www-data
COPY --chown=www-data:www-data . /var/www/html

FROM base as release

USER www-data

COPY --from=builder --chown=www-data:www-data /var/www/html/vendor /var/www/html/vendor
COPY --chown=www-data:www-data . /var/www/html
