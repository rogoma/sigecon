FROM php:7.4-fpm-buster

# Set working directory
WORKDIR /var/www/html

# Install dependencies
# librerias para el OS --> zip, vim, unzip, git, curl, build-essential
# librerias para el php --> libpq-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev zlib1g-dev libzip-dev
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    vim \
    unzip \
    git \
    curl

# Install php extensions
# Para utilizar la base de datos postgres
RUN docker-php-ext-install pdo pdo_pgsql
# Para utilizar la libreria phpoffice/phpspreadsheet
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install zip

# Install composer
RUN curl -sS https://getcomposer.org/installer |php
RUN mv composer.phar /usr/local/bin/composer

# Run composer install
# RUN php /usr/local/bin/composer install

# make www-data owner of /var/www
# RUN chown -R www-data:www-data /var/www
