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
	docker compose exec web composer $(filter-out $@,$(MAKECMDGOALS))

.PHONY: install
install:
	docker compose exec web composer install
	docker compose exec node yarn
	docker compose exec web php bin/console assets:install --symlink
	docker compose exec web php bin/console doctrine:database:create --if-not-exists
	docker compose exec web php bin/console doctrine:migration:migrate

.PHONY: console
console:
	docker compose exec web php bin/console $(filter-out $@,$(MAKECMDGOALS))

.PHONY: reset-db
reset-db:
	docker compose exec web php bin/console doctrine:database:drop -f
	docker compose exec web php bin/console doctrine:database:create
	make migrate

.PHONY: migrate
migrate:
	docker compose exec web php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: migrate-diff
migrate-diff:
	docker compose exec web php bin/console doctrine:migrations:diff

.PHONY: migrate-prev
migrate-prev:
	docker compose exec web php bin/console doctrine:migrations:migrate prev

.PHONY: load-fixtures
load-fixtures:
	docker compose exec web php bin/console doctrine:fixtures:load --purger=truncate_purger --group=dev

.PHONY: ng
ng:
	docker compose exec node ng $(filter-out $@,$(MAKECMDGOALS))

.PHONY: yarn
yarn:
	docker compose exec node yarn $(filter-out $@,$(MAKECMDGOALS))

# install java in the docker image
.PHONY: generate-api
generate-api:
	docker compose exec web php bin/console nelmio:apidoc:dump --format=json | docker compose exec -T node tee api.json > /dev/null
	docker compose exec node openapi-generator-cli generate -g typescript-angular -i api.json -o src/app/core/api --additional-properties=usePromises=true