# PIM API test

# Install

## Via Docker & Docker Compose:

_Requirements:_

- Docker & Docker Compose >= 3

```sh
docker-compose run --rm pim-api-test_php-cli composer install
```

> Composer default configuration will use a `UID=1000` and `GID=1000` for files permissions and use a local composer configuration folder at `COMPOSER_HOME=~/.composer`.

# Development with Docker Compose 🐳

To access the Symfony console:

```sh
docker-compose run --rm pim-api-test_php-cli bin/console
```
