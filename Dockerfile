FROM php:7.2-cli

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        unzip

RUN docker-php-ext-install -j$(nproc) \
        zip \
        pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
