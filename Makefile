SHELL := /bin/bash


dcktestrun := docker-compose -f docker/docker-compose.yaml run --rm
nodepsdcktestrun := docker-compose -f docker/docker-compose.yaml run --rm --no-deps
dcktestrdown := docker-compose -f docker/docker-compose.yaml down


.PHONY: tests
tests:
#	rm -rf var
	$(dcktestrun) phptest tests/bin/console cache:clear
	$(dcktestrun) phptest tests/bin/console doctrine:database:drop --env=test --force || true
	$(dcktestrun) phptest tests/bin/console doctrine:database:create --env=test
	$(dcktestrun) phptest tests/bin/console doctrine:migrations:migrate --env=test latest -n
#	$(dcktestrun) phptest tests/bin/console doctrine:fixtures:load --env=test -n
	$(dcktestrun) phptest vendor/bin/phpunit --debug -c . --coverage-html=coverage/
	$(dcktestrdown)

.PHONY: phpstan
phpstan:
	$(nodepsdcktestrun) phptest vendor/bin/phpstan analyse -c phpstan.neon --debug --memory-limit=2G

.PHONY: fixer
fixer:
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php

.PHONY: githubTests
githubTests:
	$(nodepsdcktestrun) phptest vendor/bin/phpunit -c .
