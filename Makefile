SHELL := /bin/bash

dcktestrun := docker-compose -f docker/docker-compose.yaml run --rm


.PHONY: tests
tests:
	rm -rf var
	$(dcktestrun) phptest vendor/bin/phpunit -c . --coverage-html=coverage/

.PHONY: phpstan
phpstan:
	$(dcktestrun) phptest vendor/bin/phpstan analyse -c phpstan.neon --debug --memory-limit=2G

.PHONY: fixer
fixer:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php
