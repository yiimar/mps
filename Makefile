up: docker-up
down: docker-down
restart: down up
init: docker-down-clear  mps-clear \
	docker-pull docker-build docker-up \
	mps-init
test:  mps-test
test-coverage:  mps-test-coverage
test-unit:  mps-test-unit
test-unit-coverage:  mps-test-unit-coverage

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

mps-init:  mps-composer-install  mps-assets-install \
  mps-wait-db  mps-migrations \
  mps-fixtures \
  mps-ready

mps-clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready

mps-composer-install:
	docker-compose run --rm  mps-php-cli composer install

mps-composer-update:
	docker-compose run --rm  mps-php-cli composer update

mps-assets-install:
	#docker-compose run --rm  mps-node yarn install
	#docker-compose run --rm  mps-node npm rebuild node-sass

mps-wait-db:
	docker-compose run --rm  mps-php-cli wait-for-it db:5432 -t 30

mps-migrations:
	docker-compose run --rm  mps-php-cli php bin/console doctrine:migrations:migrate --no-interaction

mps-fixtures:
	docker-compose run --rm  mps-php-cli php bin/console doctrine:fixtures:load --no-interaction

mps-ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready

mps-test:
	docker-compose run --rm  mps-php-cli php bin/phpunit

mps-test-coverage:
	docker-compose run --rm  mps-php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

mps-test-unit:
	docker-compose run --rm  mps-php-cli php bin/phpunit --testsuite=unit

mps-test-unit-coverage:
	docker-compose run --rm  mps-php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage
