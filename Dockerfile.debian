# syntax=docker/dockerfile:experimental

ARG PHP_VERSION="7.4"

FROM php:${PHP_VERSION}-fpm as base
ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV}
# @see https://pecl.php.net/package/APCU
ARG APCU_VERSION="5.1.19"
# @see https://pecl.php.net/package/mcrypt
ARG MCRYPT_VERSION="1.0.4"
# @see https://www.exploit.cz/how-to-compile-amqp-extension-for-php-8-0-via-multistage-dockerfile/
ENV EXT_AMQP_VERSION=master

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
    && git clone --branch $EXT_AMQP_VERSION --depth 1 https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && cd /usr/src/php/ext/amqp && git submodule update --init \
    && docker-php-ext-install amqp \
    && docker-php-ext-enable amqp \
    && pecl install apcu-${APCU_VERSION} \
    && docker-php-ext-enable apcu \
    && pecl install mcrypt-${MCRYPT_VERSION} \
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
RUN echo 'opcache.enable=1' > $PHP_INI_DIR/conf.d/60-lf.ini \
    && echo 'opcache.memory_consumption=256' >> $PHP_INI_DIR/conf.d/60-lf.ini \
    && echo 'opcache.max_accelerated_files=20000' >> $PHP_INI_DIR/conf.d/60-lf.ini \
    && echo 'opcache.interned_strings_buffer=16' >> $PHP_INI_DIR/conf.d/60-lf.ini \
    && echo 'realpath_cache_size=4096K' >> $PHP_INI_DIR/conf.d/60-lf.ini \
    && echo 'realpath_cache_ttl=600' >> $PHP_INI_DIR/conf.d/60-lf.ini

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

#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug \
# PHP-FPM access log send to /proc/self/fd/1
RUN find /usr/local/etc/php-fpm.d/ -type f -name "*.conf" | xargs sed -i "s/access.log = \/dev\/null/access.log = \/proc\/self\/fd\/1/g"

USER www-data
COPY --chown=www-data:www-data . /var/www/html

FROM base as release

USER root

## PHP optimizations
RUN echo 'opcache.validate_timestamps=0' >> $PHP_INI_DIR/conf.d/60-lf.ini

USER www-data

COPY --from=builder --chown=www-data:www-data /var/www/html/vendor /var/www/html/vendor
COPY --chown=www-data:www-data . /var/www/html
