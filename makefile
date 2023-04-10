SHELL=sh
.DEFAULT_GOAL := help

APP_NAME = trax_php
DOCKER_EXEC = docker exec -it ${APP_NAME}

COMPOSE_FILE = docker-compose -f docker-compose.yml

help:
	@printf "\n%s\n________________________________________________\n" $(shell basename ${APP_NAME})
	@printf "\n\033[32mAvailable commands:\n\033[0m"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep  | sed -e 's/\\$$//' | sed -e 's/##//' | awk 'BEGIN {FS = ":"}; {printf "\033[33m%s:\033[0m%s\n", $$1, $$2}'

setup: 		## Setup local development environment
	${COMPOSE_FILE} up -d
	make install
	make compile
	make migrate
	make test

rebuild: 	## Rebuild docker images
	${COMPOSE_FILE} build --no-cache
	make recreate

recreate: 	## Recreate docker container
	${COMPOSE_FILE} up -d --force-recreate

stop: 		## Stop docker containers
	${COMPOSE_FILE} stop

start: 		## Start docker containers
	${COMPOSE_FILE} start

restart: 	## Restart docker containers
	${COMPOSE_FILE} restart

remove: 	## Remove docker containers
	${COMPOSE_FILE}  rm -f --stop

shell: 		## Run container shell
	${DOCKER_EXEC} sh

install:	## Install dependencies
	${DOCKER_EXEC} composer install
	${DOCKER_EXEC} composer install --working-dir=./tools
	${DOCKER_EXEC} npm install

compile:	## Compile front
	${DOCKER_EXEC} npm run dev

compile_watch:	## Compile front
	${DOCKER_EXEC} npm run watch

dump-autoload:	## Recreate the class binding map
	${DOCKER_EXEC} composer dump-autoload

run: 		## Run microservice consumer
	${DOCKER_EXEC} php artisan app:consume

migrate: 	## Run database migrations
	${DOCKER_EXEC} php artisan migrate

test:		## Run tests
	${DOCKER_EXEC} php vendor/bin/phpunit --coverage-html tests/reports/html-coverage

cs-fix: 	## Apply source code coding standards
	${DOCKER_EXEC} php tools/vendor/bin/php-cs-fixer fix

rector-fix:	## Upgrade or refactor source code with provided rectors
	${DOCKER_EXEC} php tools/vendor/bin/rector process

analyze:	## Run static analyze
	${DOCKER_EXEC} composer static:analyze

.PHONY: help setup rebuild stop start restart remove shell install compile run migrate cs-fix rector-fix test test-mutation analyze dump-autoload
