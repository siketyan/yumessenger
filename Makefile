dev:
	docker-compose up

migration:
	docker-compose exec app ./bin/console make:migration

migrate:
	docker-compose exec app ./bin/console doctrine:migrations:migrate
