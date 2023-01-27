.PHONY: tests
tests:
	./vendor/bin/phpunit

.PHONY: cs-fix
cs-fix:
	./vendor/bin/ecs --fix
