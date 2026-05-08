.DEFAULT_GOAL := help

help: ## Show this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

bash: ## Open a bash shell in the application container
	docker exec -it product_manager bash

up: ## Start the application using Docker Compose
	docker compose up -d

down: ## Stop the application using Docker Compose
	docker compose down

logs: ## View the application logs
	docker compose logs -f

migrate: ## Run database migrations
	docker compose exec app php artisan migrate

build: ## Build the Docker images
	docker compose build

build-no-cache: ## Build the Docker images without using cache
	docker compose build --no-cache
