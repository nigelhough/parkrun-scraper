# parkrun Scraper

## Commands

Build
```
docker-compose run --rm php81 composer
docker-compose run --rm php81 composer update
docker-compose run --rm php81 composer install

docker-compose run --rm php81 composer check
docker-compose run --rm php81 composer check
docker-compose run --rm php81 composer test
docker-compose run --rm php81 composer standards
```

Execute
```
docker-compose run --rm php81 src/scraper.php
```
