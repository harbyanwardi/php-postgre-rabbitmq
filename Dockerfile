FROM php:7.4-apache
RUN apt-get update && apt-get install -y libpq-dev\
    git\
    zlib1g-dev libpng-dev\
    curl && docker-php-ext-install pdo pdo_pgsql pgsql 
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN docker-php-ext-install sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory contents
COPY . /var/www/html
# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html
# Change current user to www
USER www

WORKDIR /var/www/html








