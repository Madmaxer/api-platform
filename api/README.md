## REST CRUD application for comments handling

#### Run migrations

`docker-compose exec php bin/console doctrine:migrations:migrate -n`

#### Run fixtures (specially for tests)

`docker-compose exec php bin/console doctrine:fixtures:load -n`

#### Running tests

`docker-compose exec php vendor/bin/phing test`
