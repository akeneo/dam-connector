# DAM-connector

<!-- intro -->

# Requirements

- php >= 7.2
- composer

or

- Docker & Docker Compose >= 3


# Install

## Via Composer:
```sh
composer install
```

## Via Docker & Docker Compose:
```sh
docker-compose run --rm php-cli composer install
```

> Composer default configuration will use a `UID=1000` and `GID=1000` for files permissions and use a local composer configuration folder at `COMPOSER_HOME=~/.composer`.

> If it doesn't suit your need you can override this configuration by running `docker-compose run` commands with different value for each environment variable
```sh
UID=$UID GID=$GID COMPOSER_HOME=$HOME/.composer docker-compose run php-cli composer install
```

> Or you can use a custom [Docker Compose override file](https://docs.docker.com/compose/extends/).

# Development with Docker Compose

To start the mysql-server:
```sh
docker-compose up -d
```

To access the symfony console:
```sh
docker-compose run --rm dam-connector_php-cli bin/console
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

# Documentation

## Ubiquitous Language

### Dam Asset
DAM representation of the Asset. It's already prepared to be transformed in a PIM Asset.
It consists in an identifier `DamAssetIdentifier`, a collection of values `DamAssetValue` and a locale (that could be null).

A DAM Asset Value contains a property name and a value as string.

### Pim Asset
PIM representation of the Asset with a code and a collection of values.
An Asset is a flexible object that makes it possible to enrich products with images, videos, documents...

An Asset must be part of an Asset Family. That way, it will have its own attributes and lifecycle.

### Asset Structure

An Asset Family gathers a number of Assets that share a common attribute structure. In other words, an asset family can be considered as a template for its assets.
An asset family is made of asset attributes.

An Asset Attribute is a characteristic of an Asset for this Asset Family. It helps to describe and qualify an Asset. 
