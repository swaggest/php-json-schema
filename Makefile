PHPSTAN_VERSION ?= 0.12.43
PHPBENCH_VERSION ?= 0.16.10

deps:
	@git submodule init && git submodule update

lint:
	@test -f ${HOME}/.cache/composer/phpstan-${PHPSTAN_VERSION}.phar || (mkdir -p ${HOME}/.cache/composer/ && wget https://github.com/phpstan/phpstan/releases/download/${PHPSTAN_VERSION}/phpstan.phar -O ${HOME}/.cache/composer/phpstan-${PHPSTAN_VERSION}.phar)
	@php $$HOME/.cache/composer/phpstan-${PHPSTAN_VERSION}.phar analyze -l 7 -c phpstan.neon ./src

docker-lint:
	@docker run -v $$PWD:/app --rm phpstan/phpstan analyze -l 7 -c phpstan.neon ./src

test:
	@php -derror_reporting="E_ALL & ~E_DEPRECATED" vendor/bin/phpunit --configuration phpunit.xml

test-coverage:
	@php -derror_reporting="E_ALL & ~E_DEPRECATED" -dzend_extension=xdebug.so vendor/bin/phpunit --configuration phpunit.xml --coverage-text --coverage-clover=coverage.xml

phpbench:
	@test -f ${HOME}/.cache/composer/phpbench-${PHPBENCH_VERSION}.phar || (mkdir -p ${HOME}/.cache/composer/ && wget https://github.com/phpbench/phpbench/releases/download/${PHPBENCH_VERSION}/phpbench.phar -O ${HOME}/.cache/composer/phpbench-${PHPBENCH_VERSION}.phar)

bench: phpbench
	@php $$HOME/.cache/composer/phpbench-${PHPBENCH_VERSION}.phar run benchmarks --tag=candidate --progress=none --bootstrap=vendor/autoload.php --revs=50 --iterations=5 --retry-threshold=3 --dump-file=phpbench-candidate.xml

bench-master: phpbench
	@git checkout --detach && git fetch origin '+refs/heads/master:refs/heads/master' && git checkout master -- ./src
	@composer install --dev --no-interaction --prefer-dist
	@php $$HOME/.cache/composer/phpbench-${PHPBENCH_VERSION}.phar run benchmarks --tag=master --progress=none --bootstrap=vendor/autoload.php --revs=50 --iterations=5 --retry-threshold=3 --dump-file=phpbench-master.xml

bench-compare: phpbench
	@php $$HOME/.cache/composer/phpbench-${PHPBENCH_VERSION}.phar report --file phpbench-master.xml --file phpbench-candidate.xml --report='generator: "table", cols: [ "set" ], compare: "tag", compare_fields: ["mean"], break: ["benchmark"]'
