include .env

install:
	@$(MAKE) -s down
	@$(MAKE) -s docker-build
	@$(MAKE) -s up
	@$(MAKE) -s composer-install

up: docker-up
down: docker-down
ps:
	@docker-compose ps

docker-up:
	@docker-compose -p ${PROJECT} up -d

docker-down:
	@docker-compose -p ${PROJECT} down --remove-orphans

docker-build: \
	docker-build-app-php-cli \
	docker-build-app-php-fpm \
	docker-build-app-nginx

docker-build-app-nginx:
	@docker build --target=nginx \
	-t ${REGISTRY}/${PROJECT}-nginx:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-build-app-php-fpm:
	@docker build --target=fpm \
	-t ${REGISTRY}/${PROJECT}-php-fpm:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-build-app-php-cli:
	@docker build --target=cli \
	-t ${REGISTRY}/${PROJECT}-php-cli:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-logs:
	@docker-compose -p ${PROJECT} logs -f

app-php-cli-exec:
	@docker-compose -p ${PROJECT} run --rm php-cli $(cmd)

composer-install:
	$(MAKE) app-php-cli-exec cmd="composer install"

run-yii:
	$(MAKE) app-php-cli-exec cmd="./yii"