#!/bin/sh
set -e

if [ ! -f vendor/autoload_runtime.php ]; then
	echo "Composer install"
	composer install --no-interaction
	bin/console cache:clear
	bin/console assets:install public
	chmod -R 777 /app/var/log
	chmod -R 777 /app/var/cache
fi

if [ "$APP_ENV" != 'prod' ]; then
	echo "Making sure public / private keys for JWT exist..."
	php bin/console lexik:jwt:generate-keypair --skip-if-exists --no-interaction
fi

if grep -q DATABASE_URL= .env; then
	echo "Waiting for database to be ready..."
	ATTEMPTS_LEFT_TO_REACH_DATABASE=60
	until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
		if [ $? -eq 255 ]; then
			# If the Doctrine command exits with 255, an unrecoverable error occurred
			ATTEMPTS_LEFT_TO_REACH_DATABASE=0
			break
		fi
		sleep 1
		ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
		echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
	done

	if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
		echo "The database is not up or not reachable:"
		echo "$DATABASE_ERROR"
		exit 1
	else
		echo "The database is now ready and reachable"
	fi

	if ls -A src/Infrastructure/migrations/*.php >/dev/null 2>&1; then
		echo "Execute migrations"
		php -d memory_limit=-1 bin/console doctrine:migrations:migrate --no-interaction
	fi
fi
