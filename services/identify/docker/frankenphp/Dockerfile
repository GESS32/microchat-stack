#syntax=docker/dockerfile:1

######## Base upstream
FROM dunglas/frankenphp:1-php8.4 AS frankenphp_upstream

######## Common base
FROM frankenphp_upstream AS frankenphp_base
WORKDIR /app

# allow host bind for Symfony cache/logs
VOLUME /app/var/

# system deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    acl file gettext git curl unzip libicu-dev libzip-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libpq-dev \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions (под себя оставь нужные)
RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        zip \
        pdo_mysql \
        pdo_pgsql \
        redis

# Composer root use ok
ENV COMPOSER_ALLOW_SUPERUSER=1

# Mercure transport default (если не используешь — не мешает)
ENV MERCURE_TRANSPORT_URL=bolt:///data/mercure.db

# App PHP ini path
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

# non-root user
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} app && useradd -u ${UID} -g ${GID} -m app

# app conf
USER root
COPY --link sources/conf.d/10-app.ini $PHP_INI_DIR/app.conf.d/
COPY --link sources/Caddyfile /etc/Caddyfile

RUN mkdir -p /data /config/caddy \
 && chown -R app:app /data /config

USER app

# healthcheck по caddy admin
HEALTHCHECK --start-period=60s CMD curl -fsS http://localhost:2019/metrics || exit 1

######## Dev image
FROM frankenphp_base AS frankenphp_dev
ENV APP_ENV=dev
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch

# dev php.ini
USER root
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
 && install-php-extensions xdebug
USER app

COPY --link sources/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

# live-reload
CMD [ "frankenphp", "run", "--config", "/etc/Caddyfile", "--watch" ]

######## Prod image
FROM frankenphp_base AS frankenphp_prod
ENV APP_ENV=prod

USER root
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
USER app

COPY --link sources/conf.d/20-app.prod.ini $PHP_INI_DIR/app.conf.d/

# default command (prod)
CMD [ "frankenphp", "run", "--config", "/etc/Caddyfile" ]
