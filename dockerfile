FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip 

#enable mod_rewrite
RUN a2enmod rewrite

#install extensions
RUN docker-php-ext-install pdo pdo_mysql php5-pgsql zip

#config apache2
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
WORKDIR /var/www/html
COPY . .

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

#permission to storage and cache folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]