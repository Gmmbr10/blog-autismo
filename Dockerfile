FROM php:apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY mm.config /usr/local/apache2/conf/httpd.conf
COPY ./ /var/www/html

EXPOSE 80