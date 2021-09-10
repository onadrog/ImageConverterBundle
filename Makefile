SHELL := /bin/bash

dcktestrun := docker-compose -f docker/docker-compose.yaml run --rm

.PHONY: tests
tests:
	$(dcktestrun) phptest tests/bin/console cache:clear
	$(dcktestrun) phptest tests/bin/console doctrine:database:drop --force || true
	$(dcktestrun) phptest tests/bin/console doctrine:database:create
	$(dcktestrun) phptest tests/bin/console doctrine:migrations:migrate latest -n
	$(dcktestrun) phptest tests/bin/console doctrine:fixtures:load -n
	$(dcktestrun) phptest vendor/bin/simple-phpunit --debug -c . --coverage-html=coverage/

.PHONY: phpstan
phpstan:
	$(dcktestrun) phptest vendor/bin/phpstan analyse -c phpstan.neon --debug --memory-limit=2G

.PHONY: fixer
fixer:
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php

.PHONY: githubTests
githubTests:
	$(dcktestrun) phptest tests/bin/console cache:clear
	$(dcktestrun) phptest tests/bin/console doctrine:database:drop --env=test --force || true
	$(dcktestrun) phptest tests/bin/console doctrine:database:create --env=test
	$(dcktestrun) phptest tests/bin/console doctrine:migrations:migrate --env=test latest -n
	$(dcktestrun) phptest tests/bin/console doctrine:fixtures:load -n
	$(dcktestrun) phptest vendor/bin/phpunit -c .


ifeq (console,$(firstword $(MAKECMDGOALS)))
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  COMMAND_ARGS := $(subst :,\:,$(COMMAND_ARGS))
  $(eval $(COMMAND_ARGS):;@:)
endif

prog:
	tests/bin/console $(COMMAND_ARGS)

.PHONY: console
console: prog
	@echo prog

.PHONY: dockerbuild
dockerbuild:
	docker-compose -f docker/docker-compose.yaml build

.PHONY: dockerrm
dockerrm:
	docker-compose -f docker/docker-compose.yaml down -v --rmi local