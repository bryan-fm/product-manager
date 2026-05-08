.DEFAULT_GOAL := help

APP_CONTAINER=product_manager

help:
	@echo "Comandos disponíveis:"
	@echo "  make setup        Prepara e sobe o projeto do zero"
	@echo "  make up           Sobe os containers"
	@echo "  make down         Para os containers"
	@echo "  make test         Executa testes backend e frontend"
	@echo "  make bash         Acessa o container app"

setup:
	cp -n .env.example .env || true
	docker compose up -d --build
	docker compose exec app composer install
	docker compose exec app npm install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate:fresh --seed
	docker compose exec app npm run build
	docker compose exec app rm -f public/hot
	docker compose exec app php artisan optimize:clear

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker exec -it $(APP_CONTAINER) bash

migrate:
	docker compose exec app php artisan migrate:fresh --seed

test:
	docker compose exec app php artisan test
	docker compose exec app npm run test:unit

logs:
	docker compose logs -f