.PHONY: build up down logs

PHP_QA_IMAGE:='jakzal/phpqa:php8.2-alpine'

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

.updateQaImage:
	docker pull ${PHP_QA_IMAGE}
	touch .updateQaImage

test: .updateQaImage
	docker run --rm -it -v ${PWD}:/app -w /app ${PHP_QA_IMAGE} composer run test

phpstan: .updateQaImage
	docker run --rm -it -v ${PWD}:/app -w /app ${PHP_QA_IMAGE} composer run phpstan