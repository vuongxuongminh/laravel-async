.PHONY: tests
tests:
	./vendor/bin/phpunit --coverage-clover build/logs/phpunit/clover.xml --log-junit build/logs/phpunit/junit.xml

.PHONY: cs-fix
cs-fix:
	./vendor/bin/ecs --fix
