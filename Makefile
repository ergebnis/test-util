.PHONY: coverage cs infection it stan test

it: cs test

coverage: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

cs: vendor
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	vendor/bin/infection --min-covered-msi=80 --min-msi=60

stan: vendor
	vendor/bin/phpstan analyse --level=max src test

test: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
