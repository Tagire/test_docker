1. Сначала клонируем проект
```
git clone git@github.com:Tagire/test_docker.git
```
2. Настраиваем БД, для этого копируем .env.example
```
cp .env.dist .env
cp apps/test/.env apps/test/.env.local
cp apps/test/.env.test.dist apps/test/.env.test

и меняем параметры базы данных на свои из .env в корневой директории
```
3. Разворачиваем докер
```
docker-compose build
docker-compose up -d
docker-compose exec php php bin/console doctrine:migrations:migrate
docker-compose exec php php -d memory_limit=-1 bin/console doctrine:fixtures:load
docker-compose exec php php bin/console assets:install –symlink public/
```
4. Проверяем работу функциональных тестов
```
docker-compose exec php php -d memory_limit=-1 bin/phpunit
```
После этого проект будет доступен по адресу http://localhost
