# syntax=docker/dockerfile:experimental

FROM php:7.4-fpm as base

ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV}

ARG TIMEZONE=UTC

## OS dependencies
USER root
RUN apt-get update && apt-get install -y \
    curl \
    git \
    openssl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

## Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n' ${TIMEZONE} > $PHP_INI_DIR/conf.d/60-timezone.ini \
    && "date"

RUN apt-get update && apt-get install -y \
        libcurl4-gnutls-dev \
        libmcrypt-dev \
        libonig-dev \
        libicu-dev \
        libpq-dev \
        librabbitmq-dev \
        libxml2-dev \
    && docker-php-ext-install bcmath \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && pecl install apcu-5.1.18 \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install curl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets \
    && docker-php-ext-install xml \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/pear

## Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update
RUN composer --version

RUN chown -R www-data:www-data /var/www/

COPY ./docker/entrypoint.sh /
RUN chmod +x /entrypoint.sh && chown -R www-data:www-data /entrypoint.sh

## use production php.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

## PHP optimizations
RUN echo 'opcache.enable=1' > $PHP_INI_DIR/conf.d/60-optimizations.ini \
    && echo 'opcache.memory_consumption=256' >> $PHP_INI_DIR/conf.d/60-optimizations.ini \
    && echo 'opcache.max_accelerated_files=20000' >> $PHP_INI_DIR/conf.d/60-optimizations.ini \
    && echo 'opcache.interned_strings_buffer=16' >> $PHP_INI_DIR/conf.d/60-optimizations.ini \
    && echo 'realpath_cache_size=4096K' >> $PHP_INI_DIR/conf.d/60-optimizations.ini \
    && echo 'realpath_cache_ttl=600' >> $PHP_INI_DIR/conf.d/60-optimizations.ini

## FPM Optimizations
RUN find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/pm.max_children = 5/pm.max_children = 50/g" \
    && find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/pm = dynamic/pm = static/g" \
    && find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/;pm.max_requests = 500/pm.max_requests = 10000/g" \
    && find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/;pm.status_path/pm.status_path/g" \
    && find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/access.log = \/proc\/self\/fd\/2/access.log = \/dev\/null/g"

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]

WORKDIR /var/www/html

USER www-data

FROM base as base-coverage-xdebug

USER root

RUN apt-get update && apt-get install -y \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/* 

FROM base-coverage-xdebug as builder

# Don't execute composer scripts during composer intall
ENV COMPOSER_ARGS --no-scripts --no-progress --no-suggest --prefer-dist --no-interaction --no-autoloader

USER www-data

COPY --chown=www-data:www-data Makefile composer.* /var/www/html/
COPY --chown=www-data:www-data src/Infrastructure/Symfony5/Kernel.php /var/www/html/src/Infrastructure/Symfony5/Kernel.php
COPY --chown=www-data:www-data bin/console /var/www/html/bin/console
RUN make build

FROM builder as dev

USER root
#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug \
# PHP-FPM access log send to /proc/self/fd/1
RUN find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/access.log = \/dev\/null/access.log = \/proc\/self\/fd\/1/g"

USER www-data
COPY --chown=www-data:www-data . /var/www/html

FROM base as release

USER root

## PHP optimizations
RUN echo 'opcache.validate_timestamps=0' >> $PHP_INI_DIR/conf.d/60-optimizations.ini

USER www-data

COPY --from=builder --chown=www-data:www-data /var/www/html/vendor /var/www/html/vendor
COPY --chown=www-data:www-data . /var/www/html
