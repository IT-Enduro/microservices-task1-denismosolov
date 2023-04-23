FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
    intl \
    pdo_pgsql \
    zip \
    && a2enmod rewrite

COPY ./lumen/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "Listen 8080" >> /etc/apache2/apache2.conf \
    && sed -i 's/DocumentRoot\ \/var\/www\/html/DocumentRoot\ \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride\ None/AllowOverride\ All/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/:80/:8080/g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

CMD ["apache2-foreground"]
