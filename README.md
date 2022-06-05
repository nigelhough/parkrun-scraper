# parkrun Scraper

A library to scrape parkrun data as there is no public API available. This library and API is specifically designed to
be responsible with its polling and scraping and has caching and rate limiting to not exceed a realistic number of
requests that a single user might make in a browser.

It is designed to automate small pulls of data that you might realistically do manually for things like run reports or
club statistics. It is *NOT* for pulling large amounts of data or abusing the parkrun website.

This library may be counter-intuitive as it is open to abuse of the parkrun website, but it is in fact to do the
opposite and provide an API to the data that is responsible and fair in its usage.

## Commands used for Dev

### Build

```
docker-compose run --rm php81 composer
docker-compose run --rm php81 composer update
docker-compose run --rm php81 composer install

docker-compose run --rm php81 composer check
docker-compose run --rm php81 composer test
docker-compose run --rm php81 composer standards
```

### Execute

```
docker-compose run --rm php81 bin/cli.php
```
