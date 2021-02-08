#!/bin/bash
set -eo pipefail

whoami

# Fix needed for PHP 5.6 / 7.0 / 7.1 / 7.2 truncated logs
# https://github.com/docker-library/php/issues/207#issuecomment-395998295
PIPE=/tmp/stdout
if ! [ -p $PIPE ]; then
    mkfifo $PIPE
    chmod 666 $PIPE
fi
tail -f $PIPE &

if [ "$APP_ENV" == "prod" ]; then
  composer dump-autoload --optimize --no-dev --classmap-authoritative --quiet
  composer run-script post-install-cmd --quiet
  composer dump-env prod --quiet
  php bin/console cache:warmup --quiet
else
  composer install
fi


exec "$@"
