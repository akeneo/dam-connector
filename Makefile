DOCKER_COMPOSE = docker-compose

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d --remove-orphan ${C}

.PHONY: down
down:
	$(DOCKER_COMPOSE) down -v

# Helpers

fetch-product:
	$(DOCKER_COMPOSE) run --rm pim-api-test_php-cli php -d memory_limit=4G bin/console api:fetch-product

fetch-product-list:
	$(DOCKER_COMPOSE) run --rm pim-api-test_php-cli php -d memory_limit=4G bin/console api:fetch-product-list
