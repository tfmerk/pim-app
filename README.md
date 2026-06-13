# PIM app

Vanilla PHP PIM app.

## Building and running

1. Inside the root dir run `docker compose up --build`
2. Go to `http://localhost:8080` inside your browser

## Key features

* Routing
* Request handling
* Model View Controller setup
* ORM

## Database

* `docker compose exec app php /var/www/html/bin/drop_tables.php` --> drops all tables
* `docker compose exec app php /var/www/html/bin/migrate.php` --> migrate database tables
* `docker compose exec app php /var/www/html/bin/insert_dummy_data.php` --> insert dummy data (debug)
* `docker compose exec app php /var/www/html/bin/fetching.php` --> fetch dummy data (debug)
