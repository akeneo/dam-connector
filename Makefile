
.PHONY: install

install:
	docker-compose exec dam-fpm composer install
