ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-cli
RUN apt-get update -qq && apt-get install -yqq --no-install-recommends zip unzip libzip-dev git && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install zip
COPY --from=composer /usr/bin/composer /usr/bin/composer