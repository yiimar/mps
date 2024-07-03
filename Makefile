up: docker-up
down: docker-down
restart: down up
init: docker-down-clear ps-clear \
	docker-pull docker-build docker-up \
	ps-init
test: ps-test
test-coverage: ps-test-coverage
test-unit: ps-test-unit
test-unit-coverage: ps-test-unit-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

ps-init: ps-composer-install ps-assets-install \
 ps-wait-db ps-migrations \
 ps-fixtures \
 ps-ready

ps-clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready

ps-composer-install:
	docker-compose run --rm ps-php-cli composer install

ps-composer-update:
	docker-compose run --rm ps-php-cli composer update

ps-assets-install:
	#docker-compose run --rm ps-node yarn install
	#docker-compose run --rm ps-node npm rebuild node-sass

ps-wait-db:
	docker-compose run --rm ps-php-cli wait-for-it db:5432 -t 30

ps-migrations:
	docker-compose run --rm ps-php-cli php bin/console doctrine:migrations:migrate --no-interaction

ps-fixtures:
	docker-compose run --rm ps-php-cli php bin/console doctrine:fixtures:load --no-interaction

ps-ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready

ps-test:
	docker-compose run --rm ps-php-cli php bin/phpunit

ps-test-coverage:
	docker-compose run --rm ps-php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

ps-test-unit:
	docker-compose run --rm ps-php-cli php bin/phpunit --testsuite=unit

ps-test-unit-coverage:
	docker-compose run --rm ps-php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage
