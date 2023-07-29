.PHONY: build up down logs

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f

clean:
	docker-compose down --rmi all --remove-orphans

fresh:
	$(MAKE) clean
	$(MAKE) build
	$(MAKE) up
	docker-compose exec php sh -c "./bin/console doctrine:database:drop -f --if-exists"
	docker-compose exec php sh -c "./bin/console doctrine:database:create"
	docker-compose exec php sh -c "./bin/console doctrine:migrations:migrate"