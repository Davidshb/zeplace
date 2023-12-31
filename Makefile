SHELL := /bin/bash

.PHONY: up
up:
	docker compose up -d --build

.PHONY: down
down:
	docker compose down

.PHONY: ps
ps:
	docker compose ps

.PHONY: composer
composer:
	docker compose exec api composer $(filter-out $@,$(MAKECMDGOALS))

.PHONY: install
install:
	docker compose exec api composer install
	docker compose exec node yarn
	docker compose exec api php bin/console assets:install --symlink
	docker compose exec api php bin/console doctrine:database:create --if-not-exists
	docker compose exec api php bin/console doctrine:migration:migrate

.PHONY: console
console:
	docker compose exec api php bin/console $(filter-out $@,$(MAKECMDGOALS))

.PHONY: reset-db
reset-db:
	docker compose exec api php bin/console doctrine:database:drop -f
	docker compose exec api php bin/console doctrine:database:create
	make migrate

.PHONY: migrate
migrate:
	docker compose exec api php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: migrate-diff
migrate-diff:
	docker compose exec api php bin/console doctrine:migrations:diff

.PHONY: migrate-prev
migrate-prev:
	docker compose exec api php bin/console doctrine:migrations:migrate prev

.PHONY: load-fixtures
load-fixtures:
	docker compose exec api php bin/console doctrine:fixtures:load --purger=truncate_purger
	make redis-flush

.PHONY: ng
ng:
	docker compose exec node ng $(filter-out $@,$(MAKECMDGOALS))

.PHONY: yarn
yarn:
	docker compose exec node yarn $(filter-out $@,$(MAKECMDGOALS))

# install java in the docker image
.PHONY: generate-api
generate-api:
	docker compose exec api php bin/console nelmio:apidoc:dump --format=json | docker compose exec -T node tee api.json > /dev/null
	docker compose exec node openapi-generator-cli generate -g typescript-angular -i api.json -o src/app/core/api --additional-properties=usePromises=true

.PHONY: redis-flush
redis-flush:
	docker compose exec redis redis-cli FLUSHALL