FROM php:7.4-cli-alpine

# Imagick
RUN set -ex \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk add --no-cache --virtual .imagick-runtime-deps imagemagick \
    && apk del .phpize-deps

# Postgres extension and bash
RUN apk add --no-cache postgresql-dev bash coreutils \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

# Copy development config
RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Supervisor
RUN apk add --no-cache supervisor
COPY ./development/php-worker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENTRYPOINT ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

WORKDIR /app
