
docker compose exec app php /var/www/html/bin/drop_tables.php
docker compose exec app php /var/www/html/bin/migrate.php
docker compose exec app php /var/www/html/bin/example_insert.php
