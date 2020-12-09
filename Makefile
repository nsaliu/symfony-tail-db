composer-install:
	docker run --rm -it -u $(id -u):$(id -g) -v $(PWD):/app composer:2.0.7 composer install

composer-require:
	docker run --rm -it -u $(id -u):$(id -g) -v $(PWD):/app composer:2.0.7 composer require

phpstan:
	docker run --rm -it -u $(id -u):$(id -g) -v $(PWD):/var/www/html php:7.4.12-fpm-alpine vendor/bin/phpstan analyze -c phpstan.neon

cs-fixer:
	docker run --rm -it -u $(id -u):$(id -g) -v $(PWD):/var/www/html php:7.4.12-fpm-alpine vendor/bin/php-cs-fixer fix src --allow-risky yes
