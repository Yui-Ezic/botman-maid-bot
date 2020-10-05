init: docker-down-clear docker-pull docker-build docker-up bot-init
up: docker-up
down: docker-down
restart: down up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

bot-init: bot-composer-install bot-assets-install bot-assets-dev

bot-composer-install:
	docker-compose run --rm bot-php-cli composer install

bot-assets-install:
	docker-compose exec bot-node yarn install

bot-assets-rebuild:
	docker-compose exec bot-node npm rebuild node-sass --force

bot-assets-dev:
	docker-compose exec bot-node yarn run dev

bot-assets-watch:
	docker-compose exec bot-node yarn run watch

bot-wait-db:
	docker-compose run --rm bot-php-cli wait-for-it bot-postgres:5432 -t 30

bot-migrate:
	docker-compose run --rm bot-php-cli php artisan migrate:fresh