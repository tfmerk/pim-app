FROM php:8.5-apache

# Install basic stuff we need
RUN apt-get update \
	&& apt-get install -y \
	libpq-dev \
	git \
	unzip \
	vim \
	&& docker-php-ext-install pdo pdo_pgsql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Update Apache configuration to point to the public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy config files
COPY composer.json composer.lock ./

# Build composer stuff
RUN composer install --no-interaction --no-scripts --no-autoloader

# Copy the source code into the image for production environments
COPY . /var/www/html

# Composer optimizations
RUN composer dump-autoload -o

# Set correct permissions so Apache can read/write your files
RUN chown -R www-data:www-data /var/www/html
