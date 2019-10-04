# DAM-connector

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
docker-compose run --rm php-cli composer install
```

> Composer default configuration will use a `UID=1000` and `GID=1000` for files permissions and use a local composer configuration folder at `COMPOSER_HOME=~/.composer`.

> If it doesn't suit your need you can override this configuration by running `docker-compose run` commands with different value for each environment variable
```sh
UID=$UID GID=$GID COMPOSER_HOME=$HOME/.composer docker-compose run php-cli composer install
```

> Or you can use a custom [Docker Compose override file](https://docs.docker.com/compose/extends/).

# Development with Docker Compose 🐳

To start the MySQL server:
```sh
docker-compose up -d
```

To access the Symfony console:
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

> ⚠️ In this DAM connector skeleton we made some structural choices and those may not be the right one for your use case.

## Config

As seen in the [guide](https://api.akeneo.com/documentation/asset-manager.html) we need to define a configuration for the mapping between the DAM Assets Properties and the PIM Assets Attributes.

We choose to do this with a simple `.yaml` configuration file referenced in a Symfony DI parameter `app.dam_to_pim_mapping.config_path` (see [config/services/dam-example.yaml](./config/services/dam-example.yaml)).

## Architecture 🏗️

This skeleton uses [hexagonal architecture](https://en.wikipedia.org/wiki/Hexagonal_architecture_(software)).
The code is split in 3 main parts: **Application**, **Domain** and **Infrastructure**.

### Application

On the _Application_ side we defined a service `Application\Service\SynchronizeAssets` that is handling all the synchronization logic between the PIM and the DAM.

We also have some Interfaces that describe what actions are needed to communicate with the DAM and the PIM and you can found the concrete implementation of these classes on the _Infrastructure_ side.
- `Application\DamAdapter\`
- `Application\PimAdapter\`

The `Application\Mapping` folder hold all the logic needed to transform a DAM Asset into a PIM Asset. You may have to change this depending on your need, especially for the `Application\Mapping\AssetValueConverter` that is transforming the data format of your DAM to the PIM format.


### Domain

The Domain objects have been defined following our [ubiquitous language](https://api.akeneo.com/documentation/asset-manager.html#concepts-resources) 


### Infrastructure

Define a concrete class that implement the `Application\DamAdapter\FetchAssets` interface.
See [dam-example.yaml](./config/services/dam-example.yaml).
The `Application\Service\SynchronizeAssets` will then have access to your `FetchAssets` implementation.

We already provide a simple implementation for the `Application\PimAdapter\UpdateAsset` interface inside `Infrastructure\Pim`.
So you can upsert `PimAsset` right away.

Then we have the Symfony Console Command `Infrastructure\Command\SynchronizeAssetsCommand`
that is designed to be the entry point of the Connector via `bin/console dam-connector:assets:synchronize`
and can be called regularly, via a Cron jon for example, to synchronize assets between the DAM and the PIM.

# Run tests

## Unit tests
We suggest [phpspec](https://www.phpspec.net/) as unit test tool.
You can run them through `docker-compose run --rm dam-connector_php-cli vendor/bin/phpspec run`.

## Integration tests
