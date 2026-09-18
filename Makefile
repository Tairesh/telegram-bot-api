DC = docker compose run --rm php

install:
	mkdir -p var
	$(DC) composer install

phpcsfixer:
	$(DC) vendor/bin/php-cs-fixer fix -v

lint:
	$(DC) vendor/bin/psalm --no-cache

test:
	$(DC) vendor/bin/phpunit

check:
	mkdir -p var
	$(DC) composer validate --no-check-lock --strict
	$(DC) composer dump-autoload --dry-run --optimize --strict-psr --strict-ambiguous
	$(DC) vendor/bin/php-cs-fixer fix -v --dry-run
	$(DC) vendor/bin/psalm --no-cache
	$(DC) vendor/bin/phpunit

.PHONY: install phpcsfixer lint test check
