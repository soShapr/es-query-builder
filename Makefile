help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  clean          to delete all Makefile artifacts"
	@echo "  test           to perform unit tests.  Provide TEST to perform a specific test."
	@echo "  coverage       to perform unit tests with code coverage. Provide TEST to perform a specific test."
	@echo "  coverage-show  to show the code coverage report"

clean: 
	rm -rf build/artifacts/*

test:
	vendor/bin/phpunit

behat:
	vendor/bin/behat

test-phar: package
	[ -f build/artifacts/behat.phar ] || (cd build/artifacts && \
	wget https://github.com/Behat/Behat/releases/download/v3.0.15/behat.phar)
	[ -f build/artifacts/phpunit.phar ] || (cd build/artifacts && \
	wget https://phar.phpunit.de/phpunit.phar)
	php -dopcache.enable_cli=1 build/phar-test-runner.php --format=progress

coverage:
	vendor/bin/phpunit --testsuite=unit --coverage-html=build/artifacts/coverage $(TEST)

coverage-show:
	open build/artifacts/coverage/index.html

.PHONY: help clean test coverage coverage-show 