# PIM API test

This is a skeleton for an Akeneo PIM Connector between a DAM and the Akeneo PIM API.

It's a **PHP console application** using **Symfony 4** and **MySQL** to hold information on executed commands.

For more information on how to create your DAM Connector see the [guide](https://api.akeneo.com/documentation/asset-manager.html).

__Concepts & Resources__

Please refer to our [official documentation](https://api.akeneo.com/documentation/asset-manager.html#concepts-resources)
This is the source of truth of  our Ubiquitous Language. 


# Install

## Via Composer:

_Requirements:_
- PHP >= 7.2
- composer

```sh
composer install
```

## Via Docker & Docker Compose:

_Requirements:_
- Docker & Docker Compose >= 3


```sh
docker-compose run --rm pim-api-test_php-cli composer install
```

> Composer default configuration will use a `UID=1000` and `GID=1000` for files permissions and use a local composer configuration folder at `COMPOSER_HOME=~/.composer`.

> If it doesn't suit your need you can override this configuration by running `docker-compose run` commands with different value for each environment variable
```sh
UID=$UID GID=$GID COMPOSER_HOME=$HOME/.composer docker-compose run pim-api-test_php-cli composer install
```

> Or you can use a custom [Docker Compose override file](https://docs.docker.com/compose/extends/).

# How to use this skeleton?

This skeleton provides 2 Symfony commands:
- `dam-connector:assets:synchronize-structure` to init/synchronize the assets structure
- `dam-connector:assets:synchronize` to synchronize the assets

# Development with Docker Compose 🐳

To start the MySQL server:
```sh
docker-compose up -d
```

To access the Symfony console:
```sh
docker-compose run --rm pim-api-test_php-cli bin/console
```

## Accessing a local Akeneo PIM running in another Docker Compose

If you are hosting a parallel Akeneo PIM instance on your host (e.g. `localhost:8080`) and want to access it inside your connector,
then the simplest way is to share the Akeneo PIM network with your Connector.

It can be done in a `docker-compose.override.yml` file:
```yaml
version: '3'

networks:
    akeneo:
        external:
            name: pim_network_name # Use "docker network ls" to find the name of your Akeneo PIM network.

services:
    dam-connector_php-cli:
        networks:
            - default # Access this docker-compose network (mysql, ...)
            - akeneo # Access to Akeneo PIM network
```
